<?php

namespace App\Console\Commands;

use App\Mail\Task as MailTask;
use Illuminate\Console\Command;
use App\Models\Task as TaskModel;
use App\Services\WebODMService;
use Error;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a pending task';

    private $Task = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->taskInfo('start');

            $this->runWebODMTask();
            $this->runTileTask();
            $response = Command::SUCCESS;
        } catch (Exception $ex) {
            $this->taskError($ex);
            $response = Command::FAILURE;
        }

        $this->taskInfo('end');
        return $response;
    }

    public function runTileTask()
    {
        $this->taskInfo('[tiles] start');
        try {

            // TODO: check if there is a faild task with less than 3 attempts

            // Check if there is a running task
            $this->Task = TaskModel::where('status', 'running')->where('type', 'tiles')->orderBy('created_at')->first();
            if ($this->Task) {
                $this->taskInfo("[tiles][task {$this->Task->id}] currently running");
                $this->taskInfo('[tiles] end');
                return false;
            }

            // Check if there is a pending task
            $this->Task = TaskModel::where('status', 'pending')->where('type', 'tiles')->orderBy('created_at')->first();
            if (!$this->Task) {
                $this->taskInfo('[tiles] there is no pending task');
                $this->taskInfo('[tiles] end');
                return false;
            }

            // Check if the file exists
            $tifFile = Storage::disk('uploads')->path($this->Task->layer->file_name);
            if (!file_exists($tifFile))
                throw new Exception("[tiles][task {$this->Task->id}] file {$tifFile} not found", 1000);

            // Delete folder if exists
            $folderTile = 'tiles/' . $this->Task->layer_id;
            $tilePath = Storage::disk('uploads')->path($folderTile);
            if (file_exists($tilePath)) {
                Storage::disk('uploads')->deleteDirectory($folderTile);
                // TODO: Exception if the folder still exists
            }

            // create folder
            Storage::disk('uploads')->makeDirectory($folderTile);

            // Check if the folder exists
            if (!file_exists($tilePath))
                throw new Exception("[tiles][task {$this->Task->id}] folder {$tilePath} not found", 1000);

            // TODO: check total storage available

            // Update the task status to running
            $this->Task->status = 'running';
            $this->Task->attempts = $this->Task->attempts + 1;
            $this->Task->started_at = now();
            $this->Task->save();

            // Execute command
            $command = "gdal2tiles.py --webviewer=none --zoom=" . config('services.gdal2tiles.min_zoom') . "-" . config('services.gdal2tiles.max_zoom') . " " . $tifFile . " " . $tilePath;
            $this->taskInfo("[tiles][task {$this->Task->id}] running command {$command}");
            $res = shell_exec($command);
            if (empty($res) || strpos($res, 'error') !== false)
                throw new Exception("[tiles][task {$this->Task->id}] error running command {$command} > response: {$res}");

            // Update the task status to completed
            $this->Task->status = 'completed';
            $this->Task->completed_at = now();
            $this->Task->save();
            $this->taskInfo("[tiles][task {$this->Task->id}] completed");

            // Delete original file
            if (unlink($tifFile)) {
                $this->taskInfo("[tiles][task {$this->Task->id}] file {$tifFile} deleted");
            } else {
                $this->taskError("[tiles][task {$this->Task->id}] error deleting file {$tifFile}");
            }

            // set layer to visible true
            $this->Task->layer->visible = true;
            $this->Task->layer->save();

            $mailData = [
                'subject' => __('Task completed'),
                'task' => $this->Task,
            ];
            Mail::to($this->Task->user->email)->send(new MailTask($mailData));
        } catch (Exception | Error $e) {
            if ($this->Task) {
                $this->Task->status = 'failed';
                $this->Task->completed_at = now();
                $this->Task->exception = $e->getMessage();
                $this->Task->save();
                $this->taskError("[tiles][task {$this->Task->id}] failed");

                $mailData = [
                    'subject' => __('Task failed'),
                    'task' => $this->Task,
                ];
                Mail::to($this->Task->user->email)->send(new MailTask($mailData));
            }
            if ($e->getCode() == 1000) {
                $this->taskInfo($e->getMessage());
                Log::info($e->getMessage());
            } else {
                throw $e;
            }
        }
        $this->taskInfo('[tiles] end');
    }

    public function runWebODMTask()
    {
        $this->taskInfo('[webodm] start');
        $webodm = new WebODMService();
        //TODO Check if there is a running task
        $runningTasks = TaskModel::where('status', 'running')->where('type', 'webodm')->get();
        if ($runningTasks->count() > 0) {
            // Get the status of the running tasks
            foreach ($runningTasks as $task) {
                $taskInfo = $webodm->getTaskInfo($task->data['webodm_uuid']);
                if ($taskInfo["status"] != "success" || !$taskInfo["data"]["status"]) {
                    $this->taskError("[webodm] error getting task info from webodm");
                    $this->taskInfo('[webodm] end');
                    return false;
                }

                if ($taskInfo["data"]["status"]["code"] == 20) {
                    $task->percent_complete = round($taskInfo["data"]["progress"] ?? 0);
                    $task->save();
                    $this->taskInfo("[webodm][task {$task->id}] still running and {$task->percent_complete}% completed");
                } elseif ($taskInfo["data"]["status"]["code"] == 30) {
                    $task->status = 'failed';
                    $task->completed_at = now();
                    $task->exception = $taskInfo["data"]["status"]["errorMessage"];
                    $task->save();
                    $this->taskError("[webodm][task {$task->id}] failed");
                    $mailData = [
                        'subject' => __('Task failed'),
                        'task' => $task,
                    ];
                    Mail::to($task->user->email)->send(new MailTask($mailData));
                } elseif ($taskInfo["data"]["status"]["code"] == 40) {

                    $task->status = 'completed';
                    $task->completed_at = now();
                    $task->percent_complete = 100;
                    $this->taskInfo("[webodm][task {$task->id}] completed");
                    // check if file exists

                    $zipFile = "/tmp/webodm_{$task->uuid}.zip";

                    if (file_exists($zipFile)) {
                        unlink($zipFile);
                    }

                    $request = $webodm->downloadAssets($task->data['webodm_uuid'], $zipFile);
                    if (!$request['status']) {
                        throw new Exception($request['message']);
                    }
                    $this->taskInfo("[webodm][task {$task->id}] downloaded");

                    $zip = new \ZipArchive();
        
                    $res = $zip->open($zipFile);
                    if ($res === TRUE) {
                        $zip->extractTo("/tmp/webodm_{$task->uuid}");
                        $zip->close();
                        $this->taskInfo("[webodm][task {$task->id}] unzipped");
                        $destinyPath = uploads_path("tiles/{$task->layer_id}");

                        shell_exec("cp -r /tmp/webodm_{$task->uuid}/orthophoto_tiles {$destinyPath}");
                        // check if folder exists
                        if (!file_exists($destinyPath))
                            throw new Exception("[webodm][task {$task->id}] folder {$destinyPath} not found", 1000);

                        $info = shell_exec("gdalinfo -json /tmp/webodm_{$task->uuid}/odm_orthophoto/odm_orthophoto.tif");
                        $info = json_decode($info);
                        // validate if $info->wgs84Extent exists
                        if (!isset($info->wgs84Extent)) {
                            $message = __('The file does not have a valid projection');
                            throw new Exception($message);
                        }
                        $geom = $info->wgs84Extent->coordinates[0];
                        $task->layer->geom = json_encode($geom);

                        $task->layer->visible = true;
                        $task->layer->save();
                        $task->save();
                    } else {
                        throw new Exception("Error unzipping file");
                    }

                    $mailData = [
                        'subject' => __('Task completed'),
                        'task' => $task,
                    ];
                    Mail::to($task->user->email)->send(new MailTask($mailData));
                }
            }
        }

        if ($runningTasks->count() >= config('services.webodm.max_concurrent_tasks')) {
            $this->taskInfo("[webodm] max concurrent tasks reached (currenty running {$runningTasks->pluck('uuid')->toJson()})");
            $this->taskInfo('[webodm] end');
            return false;
        }

        $this->Task = TaskModel::where('status', 'pending')->where('type', 'webodm')->orderBy('created_at')->first();
        if (!$this->Task) {
            $this->taskInfo('[webodm] there is no pending task');
            $this->taskInfo('[webodm] end');
            return false;
        }

        $zipFile = uploads_path('zip/' . $this->Task->layer_id . '.zip');
        if (!file_exists($zipFile)) {
            $this->taskError("[webodm] zip file {$zipFile} not found");
            $this->taskInfo('[webodm] end');
            return false;
        }

        // extract zip file
        $zip = new \ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === FALSE) {
            throw new Exception("Error unzipping file {$zipFile}");
        }

        $imagesPath = uploads_path('tiles/' . $this->Task->layer_id . '_images');
        if (file_exists($imagesPath)) {
            $removedDir = removeDirectory($imagesPath);
            if (!$removedDir) {
                $this->taskError("[webodm] error removing folder " . $imagesPath);
                $this->taskInfo('[webodm] end');
                return false;
            }
        }

        $zip->extractTo($imagesPath);
        $zip->close();
        $this->taskInfo("[webodm] file {$zipFile} unzipped in {$imagesPath}");

        $files = Storage::allFiles('tiles/' . $this->Task->layer_id . '_images');
        if (count($files)) {

            $request = $webodm->createTask();
            if ($request["status"] != "success" || !$request["data"]["uuid"]) {
                $this->taskError("[webodm] error creating task in webodm");
                $this->taskInfo('[webodm] end');
                return false;
            }

            $this->Task->data = ['webodm_uuid' => $request["data"]["uuid"]];
            $this->taskInfo('[webodm] Task created in webodm with uuid ' . $this->Task->data['webodm_uuid']);


            $this->Task->status = 'running';
            $this->Task->attempts = $this->Task->attempts + 1;
            $this->Task->started_at = now();

            $counter = 1;
            foreach ($files as $file) {
                $filePath = uploads_path($file);
                $request = $webodm->uploadFile($this->Task->data['webodm_uuid'], $filePath);
                $this->taskInfo('[webodm] uploading file ' . $filePath . ' ( ' . $counter . ' of ' . count($files) . ')');
                if (!$request['status']) {
                    throw new Exception($request['message']);
                }
                $counter++;
            }
            $request = $webodm->commitTask($this->Task->data['webodm_uuid']);
            $this->taskInfo('[webodm] commiting task ' . $this->Task->data['webodm_uuid']);
            $request = $webodm->getTaskInfo($this->Task->data['webodm_uuid']);

            removeDirectory($imagesPath);


            $this->Task->save();
        } else {
            $this->taskInfo('[webodm] there is no tiles to process');
            $this->taskInfo('[webodm] end');
            $this->Task->status = 'failed';
            $this->Task->completed_at = now();
            $this->Task->exception = 'there is no tiles to process';
            $this->Task->save();
            $mailData = [
                'subject' => __('Task failed'),
                'task' => $this->Task,
            ];
            Mail::to($this->Task->user->email)->send(new MailTask($mailData));
            return false;
        }



        $this->taskInfo('[webodm] end');
    }

    private function taskInfo($message)
    {
        $message = $this->message($message);
        $this->info($message);
        Log::info($message);
    }

    private function taskError($message)
    {
        $message = $this->message($message);
        $this->error($message);
        Log::error($message);
    }

    private function message($message)
    {

        $prefix = "[$this->signature] ";
        if (strpos($message, '[') === 0) {
            $prefix = trim($prefix);
        }
        return $prefix . $message;
    }
}

<?php

namespace App\Services;

use Error;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebODMService
{

    const TASK_STATUS_QUEUED = 10;
    const TASK_STATUS_RUNNING = 20;
    const TASK_STATUS_FAILED = 30;
    const TASK_STATUS_COMPLETED = 40;
    const TASK_STATUS_CANCELED = 50;

    private $client = null;
    private $token = null;
    public function __construct()
    {
        $this->token = config('services.webodm.token');
        $this->client = new Client([
            'base_uri' => config('services.webodm.base_uri')
        ]);
    }

    public function createTask()
    {
        try {
            $postParams = [
                'options' => json_encode([["name" => "tiles", "value" => "true"]]), // Convert the JSON array to a string
            ];
            $response = $this->client->post('task/new/init?token=' . $this->token, [
                'form_params' => $postParams,
            ]);

            $response = json_decode($response->getBody(), TRUE);
            if (array_key_exists('error', $response)) throw new Exception($response['error']);

            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => $response
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return $data;
    }

    public function uploadFile($uuid, $filePath)
    {
        try {
            $response = $this->client->post('task/new/upload/' . $uuid . '?token=' . $this->token, [
                'multipart' => [
                    [
                        'name'     => 'images',
                        'contents' => fopen($filePath, 'r')
                    ]
                ]
            ]);
            $response = json_decode($response->getBody(), TRUE);
            if (array_key_exists('error', $response)) throw new Exception($response['error']);

            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => $response
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return $data;
    }

    public function commitTask($uuid)
    {
        try {
            $response = $this->client->post('task/new/commit/' . $uuid . '?token=' . $this->token);
            $response = json_decode($response->getBody(), TRUE);
            if (array_key_exists('error', $response)) throw new Exception($response['error']);

            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => $response
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return $data;
    }

    public function getTaskInfo($uuid)
    {
        try {
            $response = $this->client->get('task/' . $uuid . '/info?token=' . $this->token);
            $response = json_decode($response->getBody(), TRUE);
            if (array_key_exists('error', $response)) throw new Exception($response['error']);

            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => $response
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return $data;
    }

    public function downloadAssets($uuid, $filePath)
    {
        try {
            $response = $this->client->get('task/' . $uuid . '/download/all.zip?token=' . $this->token);
            $res = file_put_contents($filePath, $response->getBody());

            if (!$res) throw new Exception('Error saving file ' . $filePath);
            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => [
                    'file' => $filePath,
                ]
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
        return $data;
    }

    public function getTasks()
    {
        try {
            $response = $this->client->get('task/list?token=' . $this->token);
            $response = json_decode($response->getBody(), TRUE);
            if (array_key_exists('error', $response)) throw new Exception($response['error']);

            $data = [
                'status' => 'success',
                'message' => 'ok',
                'data' => $response
            ];
        } catch (Exception | Error $e) {
            Log::error($e);
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return $data;
    }
};

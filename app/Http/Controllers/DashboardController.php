<?php

namespace App\Http\Controllers;

use App\Services\WebODMService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {


        // // TODO list files

        // try {
        //     // $files = Storage::allFiles('tiles/test');
        //     // if (count($files)) {
        //     //     $webODM = new WebODMService();
        //     //     $request = $webODM->createTask();
        //     //     Log::info($request);

        //     //     if (!$request['status']) {
        //     //         throw new Exception($request['message']);
        //     //     }
        //     //     $uuid = $request['data']['uuid'];
        //     //     foreach ($files as $file) {
        //     //         $filePath = uploads_path($file);
        //     //         $request = $webODM->uploadFile($uuid, $filePath);
        //     //         Log::info($request);

        //     //         if (!$request['status']) {
        //     //             throw new Exception($request['message']);
        //     //         }
        //     //     }
        //     //     $request = $webODM->commitTask($uuid);
        //     //     Log::info($request);
        //     //     $request = $webODM->getTaskInfo($uuid);

        //     //     dd($request);
        //     // }

        //     $webODM = new WebODMService();
        //     // $request = $webODM->downloadAssets('87ea7c83-df35-44c3-aac8-5b35074fc845', 'chapalapachala.zip');
        //     $request = $webODM->downloadAssets('221c2407-b009-457c-9cff-cd6a6ffb1571', '/tmp/chapalapachala2.zip');

        //     // $request = $webODM->getTasks(); 
        //     // $request = $webODM->getTaskInfo('87ea7c83-df35-44c3-aac8-5b35074fc845');
        //     dd($request);

        //     // code 20 > Running
        //     // code 40 > Completed


        //     //221c2407-b009-457c-9cff-cd6a6ffb1571
        // } catch (Exception $e) {
        //     Log::error($e);
        //     dd($e);
        // }
        // dd('done');



        $operations = $this->getOperations();
        $totalInspections = $this->getTotalInspections($request->operation_id);
        $totalLayers = $this->getTotalLayers($request->operation_id);
        $totalUsers = $this->getTotalUsers($request->operation_id);
        $totalCompanies = $this->getTotalCompanies($request->operation_id);
        $layers = $this->getLayers($request->operation_id);

        return view('dashboard', compact(
            'operations',
            'layers',
            'totalInspections',
            'totalLayers',
            'totalUsers',
            'totalCompanies'
        ));
    }

    private function getOperations()
    {
        // DEBE SER EL MISMO FILTRO QUE INSPECCIONES (INDEX)
        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'and companies.parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            $filter .= 'and operations.id in (select operation_id from operation_user where user_id = ' . auth()->user()->id . ')';
        }

        $query = "
        select distinct(operations.id) id,
        operations.name
        from operations 
        inner join companies on companies.id = operations.company_id
        where operations.deleted_at is null
        " . $filter . "
        order by operations.name
        ";
        $operations = DB::select($query);
        return $operations;
    }

    private function getTotalInspections($operationId = null)
    {
        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'and companies.parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            $filter .= 'and operations.id in (select operation_id from operation_user where user_id = ' . auth()->user()->id . ')';
        }

        if ($operationId) {
            $filter .= ' and operations.id = ' . $operationId;
        };

        $query = "
        select count(1)
        from operations
        inner join companies on companies.id = operations.company_id
        where operations.deleted_at is null
        {$filter}
        ";
        $res = DB::select($query);
        $operations = $res[0]->count;
        return $operations;
    }

    private function getTotalLayers($operationId = null)
    {
        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'and companies.parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            $filter .= 'and operations.id in (select operation_id from operation_user where user_id = ' . auth()->user()->id . ')';
        }

        if ($operationId) {
            $filter .= ' and operations.id = ' . $operationId;
        };

        $query = "
        select count(1)
        from layers
        inner join operations on operations.id = layers.operation_id
        inner join companies on companies.id = operations.company_id
        where layers.deleted_at is null
        {$filter}
        ";
        $res = DB::select($query);
        $layers = $res[0]->count;
        return $layers;
    }

    private function getTotalUsers($operationId = null)
    {
        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'where parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            return 0;
        }

        if ($operationId) {
            $filter .= ' and operation_user.operation_id = ' . $operationId;
        };

        $query = "
        select count(1)
        from users
        inner join operation_user on operation_user.user_id = users.id
        {$filter}";
        $res = DB::select($query);
        $users = $res[0]->count;
        return $users;
    }

    private function getTotalCompanies($operationId = null)
    {

        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'and parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            return 0;
        }

        if ($operationId) {
            $filter .= ' and companies.id in ( select company_id from operations where id=' . $operationId . ' )';
        };

        $query = "
        select count(1)
        from companies
        where deleted_at is null
        {$filter}";
        $res = DB::select($query);
        $companies = $res[0]->count;
        return $companies;
    }

    private function getLayers($operationId = null)
    {

        $filter = '';
        if (auth()->user()->isOwnerProfile()) {
            $filter .= 'and companies.parent_user_id = ' . auth()->user()->id;
        } elseif (auth()->user()->isUserProfile()) {
            $filter .= 'and operations.id in (select operation_id from operation_user where user_id = ' . auth()->user()->id . ')';
        }

        if ($operationId) {
            $filter .= ' and operations.id = ' . $operationId;
        };

        $query = "
        select layer_types.name,
        (
            select count(1) 
            from layers 
            inner join operations on operations.id = layers.operation_id
            inner join companies on companies.id = operations.company_id
            where layers.deleted_at is null
            and layer_type_id = layer_types.id
            {$filter}
        ) as total
        from layer_types
        where deleted_at is null
        order by name
        ";
        $layerTypes = DB::select($query);

        $layers = [
            'labels' => [],
            'data' => []
        ];

        foreach ($layerTypes as $item) {
            $layers['labels'][] = __($item->name);
            $layers['data'][] = $item->total;
        }

        return $layers;
    }
}

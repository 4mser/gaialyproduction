<?php

namespace App\Http\Livewire\Operations;

use App\Models\Company;
use App\Models\Operation;
use App\Models\OperationType;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
        'operationTypeId' => ['except' => ''],
    ];
    protected $listeners = ['deleteOperation' => 'delete'];

    public $search = '';
    public $perPage = 10;
    public $operationId = '';
    public $operationTypeId = '';
    public $companyId = '';

    public $authUser = null;

    public function mount()
    {
        $this->authUser = auth()->user();
    }


    public function render()
    {

        $operationTypeOptions = OperationType::getOptions();
        $operations = Operation::join('companies', 'companies.id', '=', 'operations.company_id')
            ->join('operation_types', 'operation_types.id', '=', 'operations.operation_type_id')
            ->select(
                'operations.*',
                'companies.name as company_name',
                'operation_types.name as operation_type_name'
            )
            ->where(function ($q) {
                $q->where('operations.name', 'ilike', '%' . $this->search . '%');
                $q->orWhere('operations.description', 'ilike', '%' . $this->search . '%');
            });

        if (auth()->user()->isOwnerProfile()) {
            $companyOptions = Company::where('parent_user_id', $this->authUser->id)
                ->orderBy('name')->pluck('name', 'id');
            $operations->where('companies.parent_user_id', $this->authUser->id);
        } elseif (auth()->user()->isUserProfile()) {
            $companyOptions = Company::getOptions();
            $operations->whereIn('operations.id', function ($q) {
                $q->select('operation_id')
                    ->from('operation_user')
                    ->where('user_id', $this->authUser->id);
            });
        } else {
            $companyOptions = Company::getOptions();
        }

        if (!empty($this->operationTypeId)) {
            $operations->where('operations.operation_type_id', $this->operationTypeId);
        }
        if (!empty($this->companyId)) {
            $operations->where('company_id', $this->companyId);
        }

        $operations = $operations->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.operations.index', compact('companyOptions', 'operationTypeOptions', 'operations'));
    }

    public function delete($operationId)
    {
        if (auth()->user()->isFreeTrialExpired()) {
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('Free trial expired. Please upgrade your plan.')
            ]);
            return true;
        }
        DB::beginTransaction();
        try {
            $operation = Operation::find($operationId);
            foreach ($operation->layers as $layer) {
                $layer->delete();
            }
            $operation->delete();
            DB::commit();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The inspection has been successfully removed')
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to delete the inspection')
            ]);
        }
    }

    public function showMap($operationId)
    {
        session()->put('operationId', $operationId);
        return redirect()->route('map');
    }

    public function showReport($operationId)
    {
        try {
            $operation = Operation::findOrFail($operationId);
            $pdf = PDF::loadView('livewire.operations.report', compact('operation'))->output();
            return response()->streamDownload(function () use ($pdf) {
                return print($pdf);
            }, 'Reporte - ' . $operation->name . '.pdf');
        } catch (Exception $ex) {
            Log::error($ex);
            abort(404);
        }
    }
}

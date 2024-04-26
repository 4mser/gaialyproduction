<?php

namespace App\Http\Livewire\Operations;

use App\Models\Company;
use App\Models\Operation;
use App\Models\OperationType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Form extends Component
{
    public $name;
    public $description;
    public $operationTypeId;
    public $companyId;

    public $Operation;

    public $authUser = null;

    public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function render(Request $request)
    {

        $title = __('Create inspection');
        $operationTypeOptions = OperationType::getOptions();
        if (auth()->user()->isSuperAdminProfile()) {
            $companyOptions = Company::getOptions();
        } elseif (auth()->user()->isOwnerProfile()) {
            $companyOptions = Company::where('parent_user_id', $this->authUser->id)
                ->orderBy('name')
                ->pluck('name', 'id');
        } else {
            $companyOptions = [];
        }
        if ($request->id) {
            $title = __('Edit inspection');
            $this->Operation = Operation::findOrFail($request->id);
            $this->name = $this->Operation->name;
            $this->description = $this->Operation->description;
            $this->operationTypeId = $this->Operation->operation_type_id;
            $this->companyId = $this->Operation->company_id;
        }

        return view('livewire.operations.form', compact('companyOptions', 'operationTypeOptions', 'title'));
    }

    public function save()
    {

        $rules = [
            'name' => 'required',
            'description' => 'nullable',
            'operationTypeId' => 'required|exists:operation_types,id',
        ];

        if ($this->authUser->isSuperAdminProfile() || $this->authUser->isOwnerProfile()) {
            $rules['companyId'] = 'required|exists:companies,id';
        }

        $this->validate($rules, [
            'required' => __('This field is required.'),
        ]);

        try {
            if (!$this->Operation) $this->Operation = new Operation();

            $this->Operation->name = $this->name;
            $this->Operation->description = $this->description;
            $this->Operation->operation_type_id = $this->operationTypeId;

            if ($this->authUser->isSuperAdminProfile() || $this->authUser->isOwnerProfile()) {
                $this->Operation->company_id = $this->companyId;
            } else {
                $this->Operation->company_id = $this->authUser->company_id;
            }

            $this->Operation->save();

            if (auth()->user()->isUserProfile()) {
                $this->Operation->users()->syncWithoutDetaching([$this->authUser->id]);
            }

            request()->session()->flash(
                'success',
                __('The inspection has been saved successfully')
            );
            redirect()->route('inspections.index');
        } catch (Exception $ex) {
            Log::error($ex);
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('An error occurred while trying to save the inspection')
            ]);
        }
    }

    function back()
    {
        redirect()->route('inspections.index');
    }
}

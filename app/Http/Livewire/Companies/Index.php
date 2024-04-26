<?php

namespace App\Http\Livewire\Companies;

use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];
    protected $listeners = ['deleteCompany' => 'delete'];

    public $search = '';
    public $perPage = 10;
    public $toggleModal = 'hidden';
    public $companyId = null;

    public function render()
    {
        if (auth()->user()->isSuperAdminProfile()) {
            $companies = Company::where('name', 'ilike', '%' . $this->search . '%')
                ->orderBy('name')
                ->paginate($this->perPage);
        } elseif (auth()->user()->isOwnerProfile()) {
            $companies = Company::where('name', 'ilike', '%' . $this->search . '%')
                ->where('parent_user_id', auth()->user()->id)
                ->orderBy('name')
                ->paginate($this->perPage);
        } else {
            $companies = null;
        }
        return view('livewire.companies.index', compact('companies'));
    }

    public function delete($companyId)
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
            $company = Company::find($companyId);
            $company->delete();
            DB::commit();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The company has been successfully removed')
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to delete the company')
            ]);
        }
    }
}

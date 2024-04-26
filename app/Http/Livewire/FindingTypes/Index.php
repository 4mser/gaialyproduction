<?php

namespace App\Http\Livewire\FindingTypes;

use App\Models\FindingType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];
    protected $listeners = ['deleteFindingType' => 'delete'];


    public $search = '';
    public $perPage = 10;
    public $toggleModal = 'hidden';
    public $Item = null;

    public function render()
    {

        return view('livewire.finding-types.index', [
            'items' => FindingType::where('name', 'ilike', '%' . $this->search . '%')
                ->where('parent_user_id', auth()->user()->id)
                ->orderBy('name')
                ->paginate($this->perPage)
        ]);
    }

    public function delete($itemId)
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
            $item = FindingType::find($itemId);
            $item->delete();
            DB::commit();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The finding type has been successfully removed')
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error when trying to delete the finding type')
            ]);
        }
    }

    public function showImage($itemId)
    {
        DB::beginTransaction();
        try {
            $this->Item = FindingType::find($itemId);
            DB::commit();
            $this->setToggleModal();
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
        }
    }

    public function setToggleModal()
    {
        return $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }
}

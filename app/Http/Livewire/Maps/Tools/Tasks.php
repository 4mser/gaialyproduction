<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Task;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Tasks extends Component
{
   public $days = 60;
   public $search = null;
   public $tasks = [];
   public $toggleModal = 'hidden';


   public function mount()
   {
      $this->search = '';
      $this->setTasks();
   }

   public function render()
   {
      return view('livewire.maps.tools.tasks');
   }

   public function setToggleModal()
   {
      $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
   }

   public function setTasks()
   {
      $this->tasks = $this->searchTasks();
   }

   private function searchTasks($filter = [])
   {
      $startDate =  Carbon::now()->subDays($this->days)->startOfDay();
      $tasks = Task::where(function ($qry) use ($filter) {
         if (isset($filter['search'])) {
            $qry->orWhere('name', 'like', '%' . $filter['search'] . '%')
               ->orWhere('uuid', 'like', '%' . $filter['search'] . '%')
               ->orWhere('status', 'like', '%' . $filter['search'] . '%');
         }
      })
         ->whereDate('created_at', '>=', $startDate)
         ->orderBy('created_at', 'desc')
         ->limit(50)
         ->get();
      return $tasks;
   }

   public function updatedSearch()
   {
      $this->searchTasks();
   }
}

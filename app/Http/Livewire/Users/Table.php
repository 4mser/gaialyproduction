<?php

namespace App\Http\Livewire\Users;

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $queryString = ['search' => ['except' => '']];
    protected $listeners = ['toggleIsActiveUser' => 'toggleIsActive'];

    public $search = '';
    public $perPage = 10;
    public $toggleModal = 'hidden';
    public $userId = null;
    public $profileId = null;
    public $companyId = null;
    public $authUser = null;

    public function mount()
    {
        $this->authUser = auth()->user();
        if (!in_array($this->authUser->profile_id, [Profile::SUPER_ADMIN, Profile::OWNER])) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $companies = ['' => __('All companies')] + Company::getOptions()->toArray();
        $profiles  = ['' => __('All profiles')] + Profile::getOptions()->toArray();

        $users = User::where(function ($q) {
            $q->where('name', 'ilike', '%' . $this->search . '%');
            $q->orWhere('last_name', 'ilike', '%' . $this->search . '%');
            $q->orWhere('email', 'ilike', '%' . $this->search . '%');
        });
        if ($this->profileId) {
            $users->where('profile_id', $this->profileId);
        }
        if ($this->companyId) {
            $users->where('company_id', $this->companyId);
        }

        if ($this->authUser->isOwnerProfile()) {
            $users->whereIn('profile_id', [Profile::OWNER, Profile::USER]);
            $users->where('parent_user_id', $this->authUser->id);
            $companies = ['' => __('All companies')] + Company::where('parent_user_id', $this->authUser->id)->orderBy('name')->pluck('name', 'id')->toArray();
            $profiles = ['' => __('All profiles')] + Profile::whereIn('id', [Profile::OWNER, Profile::USER])->orderBy('name')->pluck('name', 'id')->toArray();
        }

        $users = $users->orderBy('name')
            ->paginate($this->perPage);
        return view('livewire.users.table', compact('users', 'companies', 'profiles'));
    }

    public function toggleIsActive($userId)
    {
        DB::beginTransaction();
        try {
            $user = User::find($userId);
            $user->is_active = !$user->is_active;
            $user->save();
            DB::commit();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The user status has been successfully changed')
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to change user status')
            ]);
        }
    }
}

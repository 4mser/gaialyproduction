<?php

namespace App\Http\Livewire\Users;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\newUser;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddCredit extends Component
{
    protected $listeners = ['addCredit' => 'addCredit'];

    public $User = null;

    public $credit = 0;

    public function mount(Request $request)
    {
        try {
            if (auth()->user()->profile_id !== Profile::SUPER_ADMIN) throw new Exception('Unauthorized');

            $this->User = User::findOrFail($request->id);
            if ($this->User->profile_id !== Profile::OWNER) throw new Exception('Unauthorized');
        } catch (Exception $ex) {
            Log::error($ex);
            return redirect()->route('users.index');
        }
    }

    public function render(Request $request)
    {
        $title = 'Add credit';
        return view('livewire.users.add-credit', compact('title'));
    }

    function volver()
    {
        return redirect()->route('users.index');
    }

    public function addCredit()
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::in([
                'user' => $this->User,
                'description' => __('Credit added'),
                'credit' => $this->credit,
            ]);
            $this->User->credit_balance = $transaction->current_credit_balance;
            $this->credit = 0;
            DB::commit();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The credit has been added successfully')
            ]);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to adding the credit')
            ]);
        }
    }
}

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
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Form extends Component
{

    public $name;
    public $lastName;
    public $email;
    public $rut;
    public $phone;
    public $profileId;
    public $companyId;
    public $title;
    public $User;

    public $authUser = null;

    public function mount()
    {
        $this->authUser = auth()->user();
        if (!in_array($this->authUser->profile_id, [Profile::SUPER_ADMIN, Profile::OWNER])) {
            return redirect()->route('dashboard');
        }
    }

    public function render(Request $request)
    {
        $title = 'Create user';
        $user = auth()->user();
        $companies = ['' => __('Please select an option')];
        if ($this->authUser->isOwnerProfile()) {
            $companies += Company::where('parent_user_id', $user->id)->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $companies += Company::orderBy('name')->pluck('name', 'id')->toArray();
        }

        if ($request->id) {
            $title = 'Edit user';
            $this->User = \App\Models\User::findOrFail($request->id);
            $this->name = $this->User->name;
            $this->lastName = $this->User->last_name;
            $this->email = $this->User->email;
            $this->rut = $this->User->rut;
            $this->phone = $this->User->phone;
            $this->title = $this->User->title;
            $this->profileId = Profile::USER;
            $this->companyId = $this->User->company_id;

        }

        return view('livewire.users.form', compact('companies', 'title'));
    }

    public function save()
    {
        $validations = array(
            'name' => 'required|min:3',
            'lastName' => 'required|string|max:255',
            'rut' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:12',
            'companyId' => 'required|exists:companies,id',
        );

        if ($this->User) {
            $validations['email'] = 'required|email|unique:users,email,' . $this->User->id;
        } else {
            $validations['email'] = 'required|email|unique:users,email';
        }

        $messages = [
            'required' => __('This field is required.'),
        ];

        $this->validate($validations, $messages);
        DB::beginTransaction();
        try {

            if ($this->User) {
                $this->User->update([
                    'name' => $this->name,
                    'last_name' => $this->lastName,
                    'email' => $this->email,
                    'rut' => $this->rut,
                    'phone' => $this->phone,
                    'title' => $this->title,
                    'company_id' => $this->companyId,
                ]);
            } else {
 
                $password = Str::random(10);
                $this->User = User::create([
                    'name' => $this->name,
                    'last_name' => $this->lastName,
                    'email' => $this->email,
                    'rut' => $this->rut,
                    'phone' => $this->phone,
                    'title' => $this->title,
                    'profile_id' => Profile::USER,
                    'company_id' => $this->companyId,
                    'parent_user_id' => auth()->user()->id,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);

                $this->User->unhashed_password = $password;

                Mail::to($this->User->email)->send(new newUser($this->User));
            }
            DB::commit();
            request()->session()->flash(
                'success',
                __('User updated successfully')
            );
            redirect('users');
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to editing the user')
            ]);
        }
    }

    function volver()
    {
        return redirect()->route('users.index');
    }
}

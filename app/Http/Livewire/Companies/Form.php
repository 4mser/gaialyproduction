<?php

namespace App\Http\Livewire\Companies;

use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Form extends Component
{
    public $name;
    public $parent_company_id;
    public $Company;

    public function render(Request $request)
    {
        if ($request->id) {
            $this->Company = Company::findOrFail($request->id);
            $this->name = $this->Company->name;
            $this->parent_company_id = $this->Company->parent_company_id;
        }

        $authUser = auth()->user();

        $companies = ['' => __('Without parent company')];
        if ($authUser->isOwnerProfile()) {
            $companies += Company::where('parent_user_id', $authUser->id)
                ->where(function ($query) use ($request) {
                    if ($request->id) {
                        $query->where('id', '!=', $request->id);
                    }
                })->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $companies += Company::where(function ($query) use ($request) {
                if ($request->id) {
                    $query->where('id', '!=', $request->id);
                }
            })->orderBy('name')->pluck('name', 'id')->toArray();
        }

        return view('livewire.companies.form', compact('companies'));
    }

    public function save()
    {

        $user = auth()->user();

        $this->validate([
            'name' => 'required',
            'parent_company_id' => 'nullable|exists:companies,id',
        ], [
            'required' => __('This field is required.'),
        ]);

        try {
            if (!$this->Company) $this->Company = new Company();

            $this->Company->name = $this->name;
            $this->Company->parent_company_id = empty($this->parent_company_id) ? null : $this->parent_company_id;
            $this->Company->parent_user_id = $user->getParentID();
            $this->Company->save();

            request()->session()->flash(
                'success',
                __('The company has been successfully saved')
            );
            request()->session()->flash(
                'fromCompany',
                __('1')
            );
            redirect()->route('users.index');
        } catch (Exception $ex) {
            Log::error($ex);
            request()->session()->flash(
                'error',
                __('An error occurred while trying to save the company')
            );
        }
    }

    function back()
    {
        redirect()->route('users.index');
    }
}

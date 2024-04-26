<?php

namespace App\Http\Livewire\FindingTypes;

use App\Models\FindingType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{

    use WithFileUploads;

    public $name;
    public $price;
    public $parentFindingType;
    public $currency = 'USD';

    public $Item;

    public $currencies;
    public $findingTypes;

    public function render(Request $request)
    {
        $this->currencies = DB::table('currencies')->get();
        $this->findingTypes = DB::table('finding_types')->where('parent_user_id', auth()->user()->getParentID())->get();
        if ($request->id) {
            $this->Item = FindingType::findOrFail($request->id);
            $this->name = $this->Item->name;
            $this->parentFindingType = $this->Item->parent_finding_type_id;
            $this->price = $this->Item->price;
            $this->currency = $this->Item->currency ?? 1;
        }
        return view('livewire.finding-types.form');
    }

    public function save()
    {

        $rules = [
            'name' => 'required',
            'parentFindingType' => 'nullable',
            'price' => 'nullable|numeric',
            'currency' => 'nullable',

        ];
        $this->validate($rules, [
            'required' => __('This field is required.'),
            'numeric' => __('This field must be numeric.'),
        ]);
        DB::beginTransaction();
        try {
            if (!$this->Item) $this->Item = new FindingType();
            $this->Item->name = $this->name;
            $this->Item->parent_finding_type_id = $this->parentFindingType;
            $this->Item->price = $this->price;
            $this->Item->currency = $this->currency;
            $this->Item->parent_user_id = auth()->user()->getParentID();

            $this->Item->save();

            DB::commit();

            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The finding type has been saved successfully')
            ]);
            redirect()->route('finding-types.index');
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error when trying to save the finding type')
            ]);
        }
    }

    function back()
    {
        redirect()->route('finding-types.index');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ImageForm extends Component
{
    public $title;
    public $openModal = false;
    
    protected $listeners = [
        'openUserForm' => 'openForm',
    ];

    public function openForm()
    {
        $this->openModal = true;
        $this->title = __('Image Details');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function hideModal()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.image-form');
    }
}

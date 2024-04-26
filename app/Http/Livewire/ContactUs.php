<?php

namespace App\Http\Livewire;

use App\Mail\ContactUs as MailContactUs;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactUs extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $sendButtonText = 'Send';
    public $formDisabled = false;

    public function render()
    {
        return view('livewire.contact-us');
    }

    public function send()
    {
        $validations = array(
            'name' => 'required|min:3',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        );

        $messages = [
            'required' => __('This field is required.'),
        ];

        $this->validate($validations, $messages);

        try {

            // Send email
            Mail::to(env('CONTACT_US_MAIL_TO'))->send(new MailContactUs($this->name, $this->email, $this->message));

            $this->name = '';
            $this->email = '';
            $this->message = '';

            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('Form sent successfully')
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => __('Error sending form. Please try again later.')
            ]);
        }
    }
}

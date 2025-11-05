<?php

namespace App\Livewire\Forms;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactRequest;
use Livewire\Component;

class ContactUsForm extends Component
{
    public $full_name = '';

    public $email = '';

    public $message = '';

    public $submitted = false;

    public function submit()
    {
        // validate the data
        $validatedData = $this->validate(
            rules: (new ContactFormRequest)->rules(),
            messages: (new ContactFormRequest)->messages()
        );

        try {
            ContactRequest::create($validatedData);

            session()->flash('success', __('messages.contact.success'));

            $this->reset(['full_name', 'email', 'message']);

            $this->submitted = true;

        } catch (\Exception $e) {
            session()->flash('error', __('messages.contact.error'));
            $this->submitted = false;

        }
    }

    public function submitAnother()
    {
        $this->submitted = false;
    }

    public function render()
    {
        return view('livewire.forms.contact-us-form');
    }
}

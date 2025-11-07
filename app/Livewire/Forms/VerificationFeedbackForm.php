<?php

namespace App\Livewire\Forms;

use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerificationFeedbackForm extends Component
{
    public $rating = 5;
    public $message = '';
    public $submitted = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'message' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'rating.required' => 'التقييم مطلوب',
        'rating.min' => 'التقييم يجب أن يكون من 1 إلى 5',
        'rating.max' => 'التقييم يجب أن يكون من 1 إلى 5',
        'message.max' => 'الرسالة لا يجب أن تتجاوز 1000 حرف',
    ];

    public function mount()
    {
        // Store intended URL for post-login redirect (only for guests)
        if (!Auth::check()) {
            session(['url.intended' => route('review')]);
        }
    }

    public function submit()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store the current URL for redirect after login
            session(['url.intended' => request()->url()]);
            session()->flash('error', 'يجب تسجيل الدخول أولاً لإرسال التقييم');
            return redirect()->route('login');
        }

        $this->validate();

        try {
            Feedback::create([
                'user_id' => Auth::id(),
                'article_title' => $this->article_title,
                'rating' => $this->rating,
                'message' => $this->message,
                'verification_result' => $this->verification_result,
            ]);

            $this->submitted = true;
            session()->flash('message', 'شكراً لك! تم إرسال تقييمك بنجاح');

            // Reset form
            $this->reset(['rating', 'message']);
            $this->rating = 5;

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إرسال التقييم. الرجاء المحاولة مرة أخرى');
        }
    }

    public function submitAnother()
    {
        $this->submitted = false;
        $this->rating = 5;
        $this->message = '';
    }

    public function redirectToLogin()
    {
        session(['url.intended' => route('review')]);
        return redirect()->route('login');
    }

    public function redirectToRegister()
    {
        session(['url.intended' => route('review')]);
        return redirect()->route('register');
    }

    public function render()
    {
        return view('livewire.forms.verification-feedback-form');
    }
}

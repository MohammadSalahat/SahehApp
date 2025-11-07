<?php

namespace App\Livewire\Pages;

use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ReviewPage extends Component
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
        // Check if user is authenticated
        if (! Auth::check()) {
            session()->flash('error', 'يجب تسجيل الدخول أولاً');

            return redirect()->route('login');
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            Feedback::create([
                'user_id' => Auth::id(),
                'rating' => $this->rating,
                'message' => $this->message,
            ]);

            $this->submitted = true;

            session()->flash('message', 'شكراً لك! تم إرسال تقييمك بنجاح');

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إرسال التقييم. الرجاء المحاولة مرة أخرى');
        }
    }

    public function render()
    {
        return view('livewire.pages.review-page');
    }
}

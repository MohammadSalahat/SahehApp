<?php

namespace App\Observers;

use App\Enums\UserTypes;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Mail\NewContactRequest;
use App\Models\ContactRequest;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ContactRequestObserver
{
    /**
     * Handle the ContactRequest "created" event.
     */
    public function created(ContactRequest $contactRequest): void
    {
        Mail::to(config('mail.from.address'))->queue(new NewContactRequest($contactRequest)); // send email

        // Get all users with type 'Admin'
        // $admins = User::where('type', UserTypes::Admin->value)->get();

        // Send notification to each admin
        // Notification::make()
        //     ->icon('heroicon-o-inbox')
        //     ->iconColor('info')
        //     ->title('New Contact Request.')
        //     ->body('Subject: '.$contactRequest->subject.'.')
        //     ->actions([
        //         Action::make('View')
        //             ->url(ContactRequestResource::getUrl('edit', ['record' => $contactRequest])),
        //         Action::make('markRead')
        //             ->markAsRead()
        //             ->color('primary'),
        //         Action::make('markUnread')
        //             ->markAsUnread()
        //             ->color(color: 'warning'),
        //     ])
        //     ->sendToDatabase($admins);
    }

    /**
     * Handle the ContactRequest "updated" event.
     */
    public function updated(ContactRequest $contactRequest): void
    {
        //
    }

    /**
     * Handle the ContactRequest "deleted" event.
     */
    public function deleted(ContactRequest $contactRequest): void
    {
        //
    }

    /**
     * Handle the ContactRequest "restored" event.
     */
    public function restored(ContactRequest $contactRequest): void
    {
        //
    }

    /**
     * Handle the ContactRequest "force deleted" event.
     */
    public function forceDeleted(ContactRequest $contactRequest): void
    {
        //
    }
}

<?php

namespace App\Models;

use App\Enums\RequestStatus;
use App\Observers\ContactRequestObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

#[ObservedBy(ContactRequestObserver::class)]
class ContactRequest extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $casts = [
        'status' => RequestStatus::class,
    ];
}

<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\RegistrationPendingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendRegistrationEmail implements ShouldQueue
{
    public $queue = 'emails';

    public function handle(UserRegistered $event)
    {
        // Enviar notificação para o usuário
        $event->user->notify(new RegistrationPendingNotification());

        // Enviar notificação para a secretaria (opcional)
        // $secretaries = \App\Models\User::where('role', 'secretary')->get();
        // Notification::send($secretaries, new NewRegistrationNotification($event->user));
    }
}

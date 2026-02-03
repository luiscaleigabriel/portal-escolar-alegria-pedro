<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationPendingNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Inscrição Recebida - IPP Alegria Pedro')
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('Recebemos sua inscrição no Portal do IPP Alegria Pedro.')
            ->line('Sua conta está em processo de análise pela nossa secretaria.')
            ->line('Por favor, dirija se as nossas instalações com os seus documentos.')
            ->line('Você receberá um email de confirmação assim que sua conta for aprovada.')
            ->line('Este processo pode levar até 48 horas úteis.')
            ->line('Obrigado por escolher nossa instituição!')
            ->salutation('Atenciosamente,<br>Equipe do IPP Alegria Pedro');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Inscrição Recebida',
            'message' => 'Sua inscrição foi recebida e está em análise.',
            'type' => 'registration',
            'user_id' => $notifiable->id,
        ];
    }
}

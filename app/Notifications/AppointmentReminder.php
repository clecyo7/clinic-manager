<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminder extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lembrete de Consulta')
            ->line('Você tem uma consulta agendada para:')
            ->line('Data: ' . $this->appointment->date_time->format('d/m/Y'))
            ->line('Hora: ' . $this->appointment->date_time->format('H:i'))
            ->line('Paciente: ' . $this->appointment->patient->name);
    }
}
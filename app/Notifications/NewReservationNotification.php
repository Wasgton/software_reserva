<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewReservationNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nova Reserva - ' . $this->reservation->property->name)
            ->line('Uma nova reserva foi realizada para sua propriedade.')
            ->line('Propriedade: ' . $this->reservation->property->name)
            ->line('Check-in: ' . $this->reservation->check_in->format('d/m/Y'))
            ->line('Check-out: ' . $this->reservation->check_out->format('d/m/Y'))
            ->line('Hóspede: ' . $this->reservation->guest->name)
            ->line('Valor Total: R$ ' . number_format($this->reservation->total_amount, 2, ',', '.'))
            ->action('Ver Detalhes', url('/reservations/' . $this->reservation->id));
    }
}

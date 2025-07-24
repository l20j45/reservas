<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Channels\WhatsAppChannel;
use App\Channels\Messages\WhatsAppMessage;



class PedidoEnviado extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', WhatsAppChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Tu cuenta ha sido aprobada!')
            ->greeting('¡Hola, ' . $this->user->nombres . '!')
            ->line('Nos complace informarte que tu perfil ha sido revisado y aprobado por nuestro equipo.')
            ->line('Ya puedes acceder a todas las funcionalidades de nuestra plataforma.')
            ->action('Ir a mi panel', url('/dashboard')) // Botón con un llamado a la acción
            ->line('¡Gracias por unirte a nuestra comunidad!');
    }

    public function toDatabase($notifiable)
    {
        // Este array se codificará a JSON y se guardará en la columna 'data'.
        return [
            'data' => 'data',
            'user' => 'user',
            'message' => 'ha comentado en tu publicación.',
        ];
    }

    public function toWhatsApp($notifiable)
    {


        return (new WhatsAppMessage)
            ->content("Notify Testing");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->user_id,
            'message' => 'Tu cuenta ha sido aprobada y ya puedes acceder a todas las funcionalidades.',
        ];
    }
}

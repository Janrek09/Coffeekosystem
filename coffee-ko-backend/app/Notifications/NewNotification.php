<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewNotification extends Notification
{
    public $message;  // The message to be sent

    /**
     * Create a new notification instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;  // Storing message to use in notification
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];  // Delivery via database and email
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,  // Storing message in database for notifications
            'time' => now()  // Timestamp of notification
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line($this->message)  // The message to be sent to the user
                    ->action('View Notifications', url('/notifications'))  // The URL to view notifications
                    ->line('Thank you for using our application!');
    }
}
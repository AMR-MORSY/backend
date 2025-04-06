<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable;
use App\Mail\UnreportedModificationsMail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnreportedModificationsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected array $data;
    public function __construct(array $data)
    {
        $this->data=$data;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        return  (new UnreportedModificationsMail($this->data))->to($notifiable->email);
       
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            'link'=>$this->data['link'],
            "message"=>$this->data['message'],
            "title"=>$this->data['title'],
            "slug"=>$this->data['slug']

        ];
    }
}

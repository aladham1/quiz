<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class GroupNotification extends Notification
{
    use Queueable;
    private $group_data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($group_data)
    {
        $this->group_data = $group_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', OneSignalChannel::class];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function routeNotificationForOneSignal()
    {
        return $this->group_data['user_ids'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject($this->group_data['title'])
            ->setBody($this->group_data['msg'])
            ->setUrl($this->group_data['url'])
            ->setIcon('https://questanya.com/icon_m.svg')
            ->setPriority(10)
            ->webButton(
                OneSignalWebButton::create('link-1')
                    ->text('Check group')
                    ->icon('https://questanya.com/icon_m.svg')
                    ->url($this->group_data['url'])
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

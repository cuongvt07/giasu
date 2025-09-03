<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RescheduleRejected extends Notification
{
    use Queueable;

    /**
     * Dữ liệu thông báo
     */
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Yêu cầu đổi lịch học đã bị từ chối')
                    ->line('Học sinh ' . $this->data['student_name'] . ' đã từ chối yêu cầu đổi lịch học môn ' . $this->data['subject'])
                    ->line('Lý do: ' . $this->data['response_note'])
                    ->action('Xem chi tiết', url('/tutor/bookings/' . $this->data['booking_id']))
                    ->line('Buổi học sẽ vẫn diễn ra theo lịch ban đầu.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}

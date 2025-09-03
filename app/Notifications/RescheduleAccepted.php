<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RescheduleAccepted extends Notification
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
                    ->subject('Yêu cầu đổi lịch học đã được chấp nhận')
                    ->line('Học sinh ' . $this->data['student_name'] . ' đã chấp nhận yêu cầu đổi lịch học môn ' . $this->data['subject'])
                    ->line('Thời gian mới: ' . $this->data['new_time'])
                    ->action('Xem chi tiết', url('/tutor/bookings/' . $this->data['booking_id']))
                    ->line('Vui lòng chuẩn bị cho buổi học theo lịch mới.');
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

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRescheduleRequested extends Notification
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
                    ->subject('Yêu cầu đổi lịch học')
                    ->line('Gia sư ' . $this->data['tutor_name'] . ' đã yêu cầu đổi lịch học môn ' . $this->data['subject'])
                    ->line('Thời gian hiện tại: ' . $this->data['original_time'])
                    ->line('Lý do: ' . $this->data['reason'])
                    ->action('Xem chi tiết', url('/student/reschedules/' . $this->data['reschedule_request_id']))
                    ->line('Vui lòng xem chi tiết và phản hồi yêu cầu.');
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

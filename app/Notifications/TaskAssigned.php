<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("You have been assigned a new task: {$this->task->title}.")
            ->line("Start: " . optional($this->task->start_date)?->format('d M Y H:i'))
            ->line("End: " . optional($this->task->end_date)?->format('d M Y H:i'))
            ->action('View Task', url("/en/home/month/"))
            ->line('Thank you for using ReSchedule!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Task Assigned',
            'message' => "You have a new task: {$this->task->title}",
            'task_id' => $this->task->id,
            'start_time' => optional($this->task->start_date)->format('d M Y H:i'),
            'end_time' => optional($this->task->end_date)->format('d M Y H:i'),
        ];
    }
}

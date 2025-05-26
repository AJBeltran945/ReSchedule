<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class DailyTaskSummary extends Notification
{
    use Queueable;

    public $tasks;

    public function __construct(Collection $tasks)
    {
        $this->tasks = $tasks;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your Tasks for Today')
            ->greeting('Good morning ' . $notifiable->name . '!')
            ->line('Here are your tasks scheduled for today:');

        foreach ($this->tasks as $task) {
            $mail->line("- {$task->title} ({$task->start_date?->format('H:i')})");
        }

        return $mail->action('View All Tasks', url('/en/home/month'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Daily Task Summary',
            'message' => "You have {$this->tasks->count()} task(s) today.",
        ];
    }
}

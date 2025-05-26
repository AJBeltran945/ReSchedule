<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class WeeklyTaskDigest extends Notification
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
            ->subject('Your Weekly Task Digest')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Here’s what you’ve got coming up this week:');

        foreach ($this->tasks as $task) {
            $mail->line("- {$task->title} (due " . optional($task->start_date)->format('D, M d H:i') . ")");
        }

        return $mail->action('See Full Schedule', url('/en/home/month'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Weekly Task Digest',
            'message' => "You have {$this->tasks->count()} upcoming task(s) this week.",
        ];
    }
}

<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Task $task, public string $action) {}

    public function build()
    {
        $subject = $this->action === 'created'
            ? 'New Task Created'
            : 'Task Completed';

        return $this->subject($subject)
            ->view('emails.task-status');
    }
}

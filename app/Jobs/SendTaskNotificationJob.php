<?php

namespace App\Jobs;

use App\Mail\TaskStatusMail;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task, public string $action) {}

    public function handle(): void
    {
        $recipient = $this->task->user->email;
        Mail::to($recipient)->send(new TaskStatusMail($this->task, $this->action));
    }
}

<?php

namespace App\Event;


use App\Entity\Task;
use Symfony\Contracts\EventDispatcher\Event;

class TaskUpdatedEvent extends Event
{
    public function __construct(
        protected Task $task
    ) {
    }

    public function getTask(): Task
    {
        return $this->task;
    }
}
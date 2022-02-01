<?php

namespace App\DataMapper;

use App\Entity\Task;
use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class TaskDataMapper
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    /**
     * @param Request $request
     * @param Task|null $task
     *
     * @return Task
     * @throws Exception
     */
    public function mapRequestContentToEntity(Request $request, ?Task $task = null): Task
    {
        if (null == $task) {
            $task = new Task();
        }

        if ($title = $request->get('title')) {
            $task->setTitle($title);
        }

        if ($desc = $request->get('desc')) {
            $task->setDesc($desc);
        }

        if ($scheduleTime = $request->get('scheduleTime')) {
            $task->setScheduleTime(new \DateTime($scheduleTime));
        }

        if ($priority = $request->get('priority')) {
            $priority = $this->entityManager->getRepository(TaskPriority::class)->find($priority['id']);

            if ($priority) {
                $task->setPriority($priority);
            }
        }

        if ($type = $request->get('type')) {
            $type = $this->entityManager->getRepository(TaskType::class)->find($type['id']);

            if ($type) {
                $task->setType($type);
            }
        }

        if ($status = $request->get('status')) {
            $status = $this->entityManager->getRepository(TaskStatus::class)->find($status['id']);

            if ($status) {
                $task->setStatus($status);
            }
        }

        return $task;
    }

}
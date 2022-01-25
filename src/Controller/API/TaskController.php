<?php

namespace App\Controller\API;

use App\Entity\Task;
use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("/api/task", name="api.task.")
 */
class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService
    )
    {
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     *
     */
    public function listAction(TaskRepository $taskRepository): Response
    {
        return $this->response($taskRepository->findAll());
    }

    /**
     * @Route("/", name=".new", methods={"POST"})
     *
     * @OA\Parameter(
     *     name="title",
     *     in="query",
     *     description="Title of task.",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     description="Description of task.",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="priority",
     *     in="query",
     *     description="Task prioriti",
     *     required=true,
     *     @OA\Schema(
     *          type="string",
     *          enum={1, 2, 3},
     *          default=2
     *     )
     * )
     * @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The field used to order rewards",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *          enum={1, 2, 3},
     *          default=1
     *     )
     * )
     * @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="Id ",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *          enum={1, 2, 3},
     *          default=1
     *     )
     * )
     * @OA\Parameter(
     *     name="schedule-time",
     *     in="query",
     *     description="Schedule date",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function addAction(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        try {
            $request = $this->transformJsonBody($request);

            if (!$request) {
                throw new \Exception();
            }

            $priority = $entityManager->getRepository(TaskPriority::class)->find($request->get('priority'));
            $status = $entityManager->getRepository(TaskStatus::class)->find($request->get('status'));
            $type = $entityManager->getRepository(TaskType::class)->find($request->get('type'));

            $task = new Task();
            $task->setTitle($request->get('title'));
            $task->setDesc($request->get('description'));
            $task->setPriority($priority);
            $task->setStatus($status);
            $task->setType($type);
            $task->setScheduleTime(new \DateTime($request->get('schedule-time')));

            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                $data = [
                    'code'=> 1024,
                    'message'=> 'Validation Failed',
                    'errors' => $errors
                ];

                return $this->response($data);
            }

            $task = $this->taskService->createTask($task);


            $data = [
                'status' => 200,
                'success' => "Post added successfully",
                'task' => $task
            ];

            return $this->response($data);
        } catch (\Exception $e) {

            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->response($data, 422);
        }

    }

    /**
     * @Route("/{id}", name=".show", methods={"GET"}, requirements={"id":"\d+"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Task::class, groups={"full"}))
     *     )
     * )
     */
    public function showAction(Task $task): JsonResponse
    {
        return $this->response($task);
    }

    /**
     * @Route("/{id}", name=".edit", methods={"PUT"}, requirements={"id":"\d+"})
     *
     *@OA\Parameter(
     *     name="title",
     *     in="query",
     *     description="Title of task.",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     description="Description of task.",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="priority",
     *     in="query",
     *     description="Task prioriti",
     *     required=true,
     *     @OA\Schema(
     *          type="string",
     *          enum={1, 2, 3},
     *          default=2
     *     )
     * )
     * @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="The field used to order rewards",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *          enum={1, 2, 3},
     *          default=1
     *     )
     * )
     * @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="Id ",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *          enum={1, 2, 3},
     *          default=1
     *     )
     * )
     * @OA\Parameter(
     *     name="schedule-time",
     *     in="query",
     *     description="Schedule date",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function editAction(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        try {
            $priority = $entityManager->getRepository(TaskPriority::class)->find($request->get('priority'));
            $status = $entityManager->getRepository(TaskStatus::class)->find($request->get('status'));
            $type = $entityManager->getRepository(TaskType::class)->find($request->get('type'));

            $task->setTitle($request->get('title'));
            $task->setDesc($request->get('description'));
            $task->setPriority($priority);
            $task->setStatus($status);
            $task->setType($type);
            $task->setScheduleTime(new \DateTime($request->get('schedule-time')));

            $task = $this->taskService->updateTask($task);

            $data = [
                'status' => 200,
                'success' => "Post updated successfully",
                'task' => $task
            ];

            return $this->response($data);
        } catch (\Exception $e) {

            $data = [
            'status' => 422,
            'errors' => $e->getMessage(),
            ];
            return $this->response($data, 422);
        }
    }

    /**
     * @Route("/{id}", name=".delete", methods={"DELETE"})
     */
    public function deleteAction(Task $task): Response
    {
        if (!$task) {
            $data = [
                'status' => 422,
                'errors' => 'Task not found.',
            ];
            return $this->response($data, 422);
        }

        $this->taskService->deleteTask($task);
        return $this->response('Task deleted successfully.');
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, $status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

}

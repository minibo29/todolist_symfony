<?php

namespace App\Controller\API;

use App\DataMapper\TaskDataMapper;
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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("/api/task", name="api.task.")
 */
class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService,
        private TaskDataMapper $taskDataMapper,
    )
    {
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns the rewards of an tasks",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Schema(
     *             type="object",
     *             ref=@Model(type=Task::class)
     *         )
     *     )
     * )
     */
    public function listAction(TaskRepository $taskRepository): Response
    {
        return $this->response($taskRepository->findAll());
    }

    /**
     * @Route("/", name=".new", methods={"POST"})
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref=@Model(type=Task::class)),
     * )
     */
    public function addAction(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $request = $this->transformJsonBody($request);
            $task = $this->taskDataMapper->mapRequestContentToEntity($request);
            $errors = $validator->validate($task);

            if (count($errors) > 0) {
                return $this->formErrorResponse($errors);
            }

            $task = $this->taskService->createTask($task);
            $data = [
                'status' => Response::HTTP_OK,
                'success' => "Post added successfully",
                'task' => $task
            ];

            return $this->response($data);
        } catch (\Exception $e) {

            $data = [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $e->getMessage(),
            ];
            return $this->response($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    /**
     * @Route("/{id}", name=".show", methods={"GET"}, requirements={"id":"\d+"})
     *
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *          ref=@Model(type=Task::class)
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
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref=@Model(type=Task::class)),
     * )
     */
    public function editAction(Request $request, Task $task, ValidatorInterface $validator): Response
    {
        try {
            $request = $this->transformJsonBody($request);
            $task = $this->taskDataMapper->mapRequestContentToEntity($request, $task);

            $errors = $validator->validate($task);

            if (count($errors) > 0) {
                return $this->formErrorResponse($errors);
            }

            $task = $this->taskService->updateTask($task);

            $data = [
                'status' => Response::HTTP_OK,
                'success' => "Post updated successfully",
                'task' => $task
            ];

            return $this->response($data);
        } catch (\Exception $e) {

            $data = [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $e->getMessage(),
            ];
            return $this->response($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @Route("/{id}", name=".delete", methods={"DELETE"})
     */
    public function deleteAction(Task $task): Response
    {
        $task = $this->taskService->deleteTask($task);
        $data = [
            'status' => Response::HTTP_OK,
            'success' => 'Task deleted successfully.',
            'task' => $task
        ];

        return $this->response($data);
    }

    /**
     * Returns a JSON response
     *
     * @param mixed $data
     * @param int $status
     * @param array<mixed> $headers
     * @return JsonResponse
     */
    public function response(mixed $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
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

    protected function formErrorResponse(ConstraintViolationListInterface $errors): Response
    {
        $errorMessages = [];

        foreach ($errors as $violation) {
            $errorMessages[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        $data = [
            'code'=> 1024,
            'message'=> 'Validation Failed',
            'errors' => $errorMessages,
        ];

        return $this->response($data);
    }
}

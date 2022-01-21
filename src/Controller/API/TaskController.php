<?php

namespace App\Controller\API;

use App\Entity\Task;
use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\TaskRepository;
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
 * @Route("/api/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="api.task_index", methods={"GET"})
     *
     */
    public function listAction(TaskRepository $taskRepository): Response
    {
        return $this->response($taskRepository->findAll());
    }

    /**
     * @Route("/", name="api.task_new", methods={"POST"})
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
            $status = $entityManager->getRepository(TaskStatus::class)->find($request->get('priority'));
            $type = $entityManager->getRepository(TaskType::class)->find($request->get('priority'));

            $task = new Task();
            $task->setTitle($request->get('title'));
            $task->setDesc($request->get('description'));
            $task->setPriority($priority);
            $task->setStatus($status);
            $task->setType($type);
            $task->setScheduleTime(new \DateTimeImmutable($request->get('schedule-time')));

            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                $data = [
                    'code'=> 1024,
                    'message'=> 'Validation Failed',
                    'errors' => $errors
                ];

                return $this->response($data);
            }
            $entityManager->persist($task);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Post added successfully",
            ];

            return $this->response($data);
        } catch (\Exception $e) {

            print_r('<pre>');
            print_r($e->getMessage());
            print_r('</pre>');
            exit;
            
            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->response($data, 422);
        }

    }

    /**
     * @Route("/{id}", name="api.task_show", methods={"GET"}, requirements={"id":"\d+"})
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
     * @Route("/{id}", name="api.task_edit", methods={"PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="api.task_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api.task_index', [], Response::HTTP_SEE_OTHER);
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

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

}

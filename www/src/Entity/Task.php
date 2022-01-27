<?php

namespace App\Entity;

use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Task implements JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @SerializedName("id")
     */
    private $id;

    /**
     * @ORM\Column(name="strTaskName", type="string", length=50)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(name="strTaskDesc", type="text", length=255)
     * @Assert\NotBlank
     */
    private $desc;

    /**
     * @ORM\Column(name="dtmScheduleTime", type="date", nullable=true)
     */
    private $scheduleTime;

    /**
     * @ORM\ManyToOne(targetEntity=TaskPriority::class)
     * @ORM\JoinColumn
     * @Assert\NotBlank
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity=TaskType::class)
     * @Assert\NotBlank
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=TaskStatus::class)
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(?TaskPriority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getType(): ?TaskType
    {
        return $this->type;
    }

    public function setType(?TaskType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function setStatus(?TaskStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScheduleTime(): ?\DateTimeImmutable
    {
        return $this->scheduleTime;
    }

    /**
     * @param \DateTimeImmutable $scheduleTime
     *
     * @return Task
     */
    public function setScheduleTime(\DateTimeImmutable  $scheduleTime): self
    {
        $this->scheduleTime = $scheduleTime;
        return $this;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->desc,
            'priority' => $this->priority,
            'status' => $this->status,
            'type' => $this->type,
            'schedule-time' => $this->scheduleTime->format('Y-m-d'),
        ];
    }
}

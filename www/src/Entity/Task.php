<?php

namespace App\Entity;

use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository", repositoryClass=TaskRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Task implements JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @SerializedName("id")
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="strTaskName", type="string", length=50)
     * @Assert\NotBlank
     */
    private ?String $title = null;

    /**
     * @ORM\Column(name="strTaskDesc", type="text", length=255)
     * @Assert\NotBlank
     */
    private ?String $desc = null;

    /**
     * @ORM\Column(name="dtmScheduleTime", type="date", nullable=true)
     * @var \DateTime|null
     */
    private $scheduleTime = null;

    /**
     * @ORM\ManyToOne(targetEntity=TaskPriority::class)
     * @ORM\JoinColumn
     * @Assert\NotBlank
     */
    private ?TaskPriority $priority = null;

    /**
     * @ORM\ManyToOne(targetEntity=TaskType::class)
     * @Assert\NotBlank
     */
    private ?TaskType $type = null;

    /**
     * @ORM\ManyToOne(targetEntity=TaskStatus::class)
     * @Assert\NotBlank
     */
    private ?TaskStatus $status = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var DateTimeImmutable|null
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     * @return Task
     */
    public function setDesc(string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getScheduleTime(): ?\DateTime
    {
        return $this->scheduleTime;
    }

    /**
     * @param \DateTime $scheduleTime
     *
     * @return Task
     */
    public function setScheduleTime(\DateTime  $scheduleTime): self
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
        $this->setUpdatedAt(new DateTimeImmutable('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable('now'));
        }
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'priority' => $this->priority,
            'status' => $this->status,
            'type' => $this->type,
            'schedule-time' => $this->scheduleTime->format('Y-m-d'),
        ];
    }
}

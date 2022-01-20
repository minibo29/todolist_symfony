<?php

namespace App\Entity;

use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\OneToOne(targetEntity=TaskPriority::class, cascade={"persist", "remove"})
     * @Assert\NotBlank
     */
    private $priority;

    /**
     * @ORM\OneToOne(targetEntity=TaskType::class, cascade={"persist", "remove"})
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
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
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

}

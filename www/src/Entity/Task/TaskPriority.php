<?php

namespace App\Entity\Task;

use App\Repository\Task\TaskPriorityRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=TaskPriorityRepository::class)
 */
class TaskPriority implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TaskPriority
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return TaskPriority
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize(): string
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->label,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized The string representation of the object.
     */
    public function unserialize($serialized): void
    {
        list (
            $this->id,
            $this->name,
            $this->label,
            ) = unserialize($serialized, array('allowed_classes' => false));
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}

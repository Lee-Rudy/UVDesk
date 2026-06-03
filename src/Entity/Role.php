<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

//insert role :
// INSERT INTO role (type, access, created_at, updated_at)
// VALUES
// ('ADMIN', '["CREATE_USER", "READ_USER", "UPDATE_USER", "DELETE_USER"]', NOW(), NOW()),
// ('MANAGER', '["READ_USER", "UPDATE_USER"]', NOW(), NOW()),
// ('USER', '["READ_USER"]', NOW(), NOW());

// 'ADMIN' => [
    //             'CREATE_USER',
    //             'READ_USER',
    //             'UPDATE_USER',
    //             'DELETE_USER'
    //         ],

    //         'MANAGER' => [
    //             'READ_USER',
    //             'UPDATE_USER'
    //         ],

    //         default => [
    //             'READ_USER'
    //         ] 

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // type du rôle : ADMIN, MANAGER, USER
    #[ORM\Column(length: 50, unique: true)]
    private ?string $type = null;

    // accès/permissions en JSON
    #[ORM\Column(type: 'json')]
    private array $access = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getAccess(): array
    {
        return $this->access;
    }

    public function setAccess(array $access): static
    {
        $this->access = $access;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
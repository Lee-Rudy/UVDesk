<?php

namespace App\Entity;

use App\Repository\InfoUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoUserRepository::class)]
class InfoUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //variable name
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    //variable age
     #[ORM\Column]
    private ?int $age = null;

    //variable date + heure créer
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    //variable date + heure modifier
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    //insert de test
    
    // insert into info_user (id, name, age, created_at, updated_at) values (1, 'Kevin', 26, '2024-06-01 12:00:00', '2024-06-01 12:00:00');

    //getters et setters

    //id
    public function getId(): ?int
    {
        return $this->id;
    }

    //name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    //age
    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;
        return $this;
    }

    //date+time créer
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    //date+time modifier
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

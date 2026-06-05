<?php

namespace App\Entity;

use App\Repository\AuthUserRepository;
use Doctrine\ORM\Mapping as ORM;


//user test 
// {
//   "firstName": "John",
//   "lastName": "Doe",
//   "email": "john@gmail.com",
//   "password": "1234",
//   "role": "ADMIN"
// }

#[ORM\Entity(repositoryClass: AuthUserRepository::class)]
class AuthUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // prénom
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    // nom
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    // email de connexion
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    // mot de passe haché
    #[ORM\Column(length: 255)]
    private ?string $pswHash = null;

    // rôle utilisateur
    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    // compte actif ou non
    #[ORM\Column]
    private ?bool $isActive = true;

    // dernière connexion
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastLoginAt = null;

    // IP dernière connexion
    #[ORM\Column(length: 45, nullable: true)]
    private ?string $lastLoginIp = null;

    // access token
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessToken = null;

    // date expiration access token
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $accessTokenExpiresAt = null;

    // refresh token
    #[ORM\Column(length: 512, nullable: true)]
    private ?string $refreshToken = null;

    // date création
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // date modification
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    //getters et setters 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPswHash(): ?string
    {
        return $this->pswHash;
    }

    public function setPswHash(string $pswHash): static
    {
        $this->pswHash = $pswHash;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeImmutable $lastLoginAt): static
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    public function getLastLoginIp(): ?string
    {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp(?string $lastLoginIp): static
    {
        $this->lastLoginIp = $lastLoginIp;
        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getAccessTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->accessTokenExpiresAt;
    }

    public function setAccessTokenExpiresAt(?\DateTimeImmutable $accessTokenExpiresAt): static
    {
        $this->accessTokenExpiresAt = $accessTokenExpiresAt;
        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;
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
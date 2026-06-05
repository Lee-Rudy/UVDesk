<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Repository\AuthUserRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


//data test create user
// {
//   "firstName": "John",
//   "lastName": "Doe",
//   "email": "john@gmail.com",
//   "password": "1234",
//   "role": "ADMIN"
// }

//data test login
// {
//   "email": "john@gmail.com",
//   "password": "1234",
// }

#[Route('/auth')]
class AuthUserController extends AbstractController
{
    #[Route('/create-user', name: 'auth_simulation_create_user', methods: ['POST'])]
    public function createUser(
        Request $request,
        EntityManagerInterface $em,
        RoleRepository $roleRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $roleType = $data['role'] ?? 'USER';

        if (!$email || !$password) {
            return $this->json([
                'status' => false,
                'message' => 'Email et mot de passe obligatoires'
            ], 400);
        }

        $role = $roleRepository->findOneBy([
            'type' => $roleType
        ]);

        if (!$role) {
            return $this->json([
                'status' => false,
                'message' => 'Rôle introuvable',
                'role_requested' => $roleType
            ], 404);
        }

        $user = new AuthUser();

        $user->setFirstName($data['firstName'] ?? null);
        $user->setLastName($data['lastName'] ?? null);
        $user->setEmail($email);

        $user->setPswHash(password_hash($password, PASSWORD_BCRYPT));

        // ici on enregistre l'objet Role, pas un simple texte
        $user->setRole($role);

        $user->setIsActive(true);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($user);
        $em->flush();

        return $this->json([
            'status' => true,
            'message' => 'Utilisateur créé avec succès',
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => [
                    'id' => $user->getRole()->getId(),
                    'type' => $user->getRole()->getType(),
                    'access' => $user->getRole()->getAccess()
                ],

                // à garder seulement pour test, pas en production
                'password_hash_in_database' => $user->getPswHash()
            ]
        ]);
    }

    #[Route('/login', name: 'auth_simulation_login', methods: ['POST'])]
    public function login(
        Request $request,
        AuthUserRepository $repository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json([
                'status' => false,
                'message' => 'Email et mot de passe obligatoires'
            ], 400);
        }

        $user = $repository->findOneBy([
            'email' => $email
        ]);

        if (!$user) {
            return $this->json([
                'status' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        if (!$user->isActive()) {
            return $this->json([
                'status' => false,
                'message' => 'Compte désactivé'
            ], 403);
        }

        if (!password_verify($password, $user->getPswHash())) {
            return $this->json([
                'status' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }

        $accessToken = bin2hex(random_bytes(32));
        $refreshToken = bin2hex(random_bytes(64));

        $user->setRefreshToken($refreshToken);
        $user->setLastLoginAt(new \DateTimeImmutable());
        $user->setLastLoginIp($request->getClientIp());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->json([
            'status' => true,
            'message' => 'Connexion réussie',

            'account' => [
                'isActive' => $user->isActive(),
                'message' => 'Compte actif'
            ],

            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => [
                    'id' => $user->getRole()->getId(),
                    'type' => $user->getRole()->getType()
                ],
                'permissions' => $user->getRole()->getAccess()
            ],

            'token' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in_seconds' => 3600,
                'expires_at' => (
                    new \DateTimeImmutable('+1 hour')
                )->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
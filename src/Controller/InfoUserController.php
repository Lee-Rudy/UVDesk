<?php

//==============================================
//SYNTAX EN ATTRIBUTES LE PLUS RECENT
//==============================================
namespace App\Controller;

use App\Entity\InfoUser;
use App\Repository\InfoUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

//ici dans le controller le use attribute ne marche pas , faut faire use annotation

// use Symfony\Component\Routing\Attribute\Route;

//cette route est faite pour  le controller en mode attribute, il a pour rôle  du chemin path dans l'url 
// à noter que le lien commence toutjours par "/" 
// faux lien : api/info-user/create
// vrai lien : /api/info-user/create

#[Route('/info-user')]
class InfoUserController extends AbstractController
{
    #[Route('/create', name: 'api_info_user_create', methods: ['POST'])]
    public function create(EntityManagerInterface $em): JsonResponse
    {
        // $user = $this->getUser();

        $user = new InfoUser();
        $user->setName('johnDoe');
        $user->setAge(25);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'Utilisateur sauvgardé dans la basse de donnée avec succès',
            'data' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'createdAt' => $user->getCreatedAt()->format('H:i d/m/y'),
                'updatedAt' => $user->getUpdatedAt()->format('H:i d/m/y'),
            ]
        ]);
    }

    #[Route('/api/list', name: 'api_info_user_list', methods: ['GET'])]
    public function list(InfoUserRepository $repository): JsonResponse
    {
        $users = $repository->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'createdAt' => $user->getCreatedAt()->format('H:i d/m/y'),
                'updatedAt' => $user->getUpdatedAt()->format('H:i d/m/y'),
            ];
        }

        return $this->json($data);
    }
}

//==============================================
//SYNTAX EN ANNOTATIONS
//==============================================

// namespace App\Controller;

// use App\Entity\InfoUser;
// use App\Repository\InfoUserRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Annotation\Route;

// /**
//  * @Route("/info-user")
//  */
// class InfoUserController extends AbstractController
// {
//     /**
//      * @Route("/create", name="api_info_user_create", methods={"POST"})
//      */
//     public function create(EntityManagerInterface $em): JsonResponse
//     {
//         $user = new InfoUser();
//         $user->setName('johnDoe');
//         $user->setAge(25);
//         $user->setCreatedAt(new \DateTimeImmutable());
//         $user->setUpdatedAt(new \DateTimeImmutable());

//         $em->persist($user);
//         $em->flush();

//         return $this->json([
//             'message' => 'Utilisateur enregistré avec succès',
//             'data' => [
//                 'id' => $user->getId(),
//                 'name' => $user->getName(),
//                 'age' => $user->getAge(),
//                 'createdAt' => $user->getCreatedAt()->format('H:i d/m/y'),
//                 'updatedAt' => $user->getUpdatedAt()->format('H:i d/m/y'),
//             ]
//         ]);
//     }

//     /**
//      * @Route("/api/list", name="api_info_user_list", methods={"GET"})
//      */
//     public function list(InfoUserRepository $repository): JsonResponse
//     {
//         $users = $repository->findAll();

//         $data = [];

//         foreach ($users as $user) {
//             $data[] = [
//                 'id' => $user->getId(),
//                 'name' => $user->getName(),
//                 'age' => $user->getAge(),
//                 'createdAt' => $user->getCreatedAt()->format('H:i d/m/y'),
//                 'updatedAt' => $user->getUpdatedAt()->format('H:i d/m/y'),
//             ];
//         }

//         return $this->json($data);
//     }
// }

// CREATE TABLE User (
//     id BIGINT AUTO_INCREMENT,
//     first_name VARCHAR(255) NULL,
//     last_name VARCHAR(255) NULL,
//     email VARCHAR(255) NOT NULL,
//     psw_hash VARCHAR(255) NOT NULL,
//     Role BIGINT NULL,
//     is_active BOOLEAN DEFAULT TRUE,
//     last_login_at DATETIME NULL,
//     last_login_ip VARCHAR(45) NULL,
//     refresh_token VARCHAR(512) NULL, -- Stocke le token de rafraîchissement
//     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
//     update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
//     PRIMARY KEY (id),
    
//     INDEX idx_user_role (Role),
//     INDEX idx_user_email (email),
//     INDEX idx_user_refresh (refresh_token) -- Index pour chercher rapidement le token
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


// INSERT INTO User (
//     first_name, 
//     last_name, 
//     email, 
//     psw_hash, 
//     Role, 
//     is_active, 
//     last_login_at, 
//     last_login_ip, 
//     refresh_token
// ) VALUES (
//     'Jean', 
//     'Dupont', 
//     'jean.dupont@example.com', 
//     '$2y$10$abcdefghijklmnopqrstuvwx', -- Exemple de hash factice (ex: bcrypt)
//     1, 
//     TRUE, 
//     CURRENT_TIMESTAMP, 
//     '192.168.1.50', 
//     'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.dummy_refresh_token_string'
// );




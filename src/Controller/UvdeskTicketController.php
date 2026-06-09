<?php

namespace App\Controller;

use App\Entity\UvdeskTicketReference;
use App\Repository\UvdeskTicketReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\Ticket as UvdeskTicket;
use Webkul\UVDesk\MailboxBundle\Services\MailboxService;

#[Route('/api/uvdesk')]
class UvdeskTicketController extends AbstractController
{
    #[Route('/email/inbound', name: 'api_uvdesk_email_inbound', methods: ['POST'])]
    public function inboundEmail(Request $request, MailboxService $mailboxService, EntityManagerInterface $entityManager): JsonResponse
    {
        $rawEmail = $request->request->get('email');

        if (empty($rawEmail)) {
            $rawEmail = trim($request->getContent());
        }

        if (empty($rawEmail)) {
            return $this->json([
                'success' => false,
                'message' => 'Le champ "email" est requis. Envoyez le contenu brut du message MIME.',
            ], 400);
        }

        try {
            $processed = $mailboxService->processMail($rawEmail);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        $ticketId = $processed['content']['ticket'] ?? null;

        if (! empty($ticketId)) {
            $this->storeUvdeskReference($ticketId, $processed, $entityManager);
        }

        return $this->json([
            'success' => true,
            'ticketId' => $ticketId,
            'processed' => $processed,
        ]);
    }

    #[Route('/tickets', name: 'api_uvdesk_ticket_list', methods: ['GET'])]
    public function listTickets(UvdeskTicketReferenceRepository $referenceRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $references = $referenceRepository->findAll();
        $data = [];

        foreach ($references as $reference) {
            $uvdeskTicket = $entityManager->getRepository(UvdeskTicket::class)->find($reference->getUvdeskTicketId());
            $ticketStatus = null;
            $createdAt = $reference->getCreatedAt();
            $subject = $reference->getSubject();
            $customerEmail = $reference->getCustomerEmail();

            if ($uvdeskTicket) {
                $ticketStatus = $uvdeskTicket->getStatus()?->getCode() ?? $reference->getStatus();
                $subject = $uvdeskTicket->getSubject() ?? $subject;
                $customerEmail = $uvdeskTicket->getCustomer()?->getEmail() ?? $customerEmail;
                $createdAt = $uvdeskTicket->getCreatedAt() ? \DateTimeImmutable::createFromMutable($uvdeskTicket->getCreatedAt()) : $createdAt;
            }

            $data[] = [
                'referenceId' => $reference->getId(),
                'uvdeskTicketId' => $reference->getUvdeskTicketId(),
                'customerEmail' => $customerEmail,
                'subject' => $subject,
                'status' => $ticketStatus,
                'createdAt' => $createdAt->format('Y-m-d H:i:s'),
                'updatedAt' => $reference->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    private function storeUvdeskReference(int $ticketId, array $processed, EntityManagerInterface $entityManager): void
    {
        $reference = new UvdeskTicketReference();
        $reference->setUvdeskTicketId($ticketId);

        if (! empty($processed['content']['from'])) {
            $reference->setCustomerEmail($processed['content']['from']);
        }

        if (! empty($processed['content']['subject'])) {
            $reference->setSubject($processed['content']['subject']);
        }

        $ticket = $entityManager->getRepository(UvdeskTicket::class)->find($ticketId);

        if ($ticket) {
            $reference->setStatus($ticket->getStatus()?->getCode() ?? null);
        }

        $reference->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($reference);
        $entityManager->flush();
    }
}

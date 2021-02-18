<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContactService;
use ContactMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    const MAX_ITEMS_PER_PAGE = 3;
    private ContactService $contactService;

    /**
     * ContactController constructor.
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @Route("/contact", methods={"POST"})
     */
    public function createContact(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $contactName = $requestData['contactName'];
        $contactId = $this->contactService->createContact($contactName);

        return new JsonResponse($contactId);
    }

    /**
     * @Route("/contacts", methods={"GET"})
     */
    public function listActiveContact(Request $request): Response
    {
        $page = $request->query->get('page');
        $pageSize = $request->query->get('pageSize');
        if (!$pageSize || $pageSize > self::MAX_ITEMS_PER_PAGE) {
            $pageSize = self::MAX_ITEMS_PER_PAGE;
        }
        if (!$page || $page < 1) {
            $page = 1;
        }

        $paginationInfo = $this->contactService->calculatePaginationInfo($pageSize);
        $contacts = $this->contactService->listActiveContact($page, $pageSize);
        $contactsDTO = [];
        foreach ($contacts as $contact) {
            $contactsDTO[] = ContactMapper::ContactToDto($contact);
        }

        return new JsonResponse([
            'items' => $contactsDTO,
            'paginationInfo' => $paginationInfo,
        ]);
    }

    /**
     * @Route("/contact/{contactId}", methods={"DELETE"})
     */
    public function deleteContact(string $contactId): Response
    {
        $this->contactService->deleteContact($contactId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/contact/{contactId}", methods={"PUT"})
     */
    public function updateContact(string $contactId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $contactName = $requestData['contactName'];
        $this->contactService->updateContactName($contactId, $contactName);

        return new JsonResponse('', Response::HTTP_OK);
    }

    /**
     * @Route("/contact/{contactId}/group", methods={"POST"})
     */
    public function addContactGroup(string $contactId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupId = $requestData['groupId'];
        $this->contactService->addContactToGroup($contactId, $groupId);

        return new JsonResponse('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/contact/{contactId}/group/{groupId}", methods={"DELETE"})
     */
    public function removeContactGroup(string $contactId, string $groupId): Response
    {
        $this->contactService->removeContactFromGroup($contactId, $groupId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}

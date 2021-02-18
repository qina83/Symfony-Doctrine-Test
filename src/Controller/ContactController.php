<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContactServiceInterface;
use ContactMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    const MAX_ITEMS_PER_PAGE = 3;
    private ContactServiceInterface $contactService;

    /**
     * ContactController constructor.
     */
    public function __construct(ContactServiceInterface $contactService)
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
        $pageSizePar = $request->query->get('pageSize');
        $pagePar = $request->query->get('page');

        $pageSize = max(1, intval($pageSizePar));
        $page = min(self::MAX_ITEMS_PER_PAGE, intval($pagePar));

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

    /**
     * @Route("/contact/{contactId}/address", methods={"POST"})
     */
    public function addContactAddress(string $contactId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupId = $requestData['groupId'];
    }

    /**
     * @Route("/contact/{contactId}/address/{addressId}", methods={"DELETE"})
     */
    public function removeContactAddress(string $contactId, string $addressId): Response
    {

    }

}

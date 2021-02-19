<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PersonService;
use PersonMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    const MAX_ITEMS_PER_PAGE = 3;
    private PersonService $personService;

    /**
     * PersonController constructor.
     */
    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * @Route("/persons", methods={"POST"})
     */
    public function createContact(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $contactName = $requestData['contactName'];
        $contactId = $this->personService->createContact($contactName);

        return new JsonResponse($contactId);
    }

    /**
     * @Route("/persons", methods={"GET"})
     */
    public function listActiveContact(Request $request): Response
    {
        $pageSizePar = $request->query->get('pageSize');
        $pagePar = $request->query->get('page');

        $pageSize = max(1, intval($pageSizePar));
        $page = min(self::MAX_ITEMS_PER_PAGE, intval($pagePar));

        $paginationInfo = $this->personService->calculatePaginationInfo($pageSize);
        $contacts = $this->personService->listActiveContact($page, $pageSize);
        $contactsDTO = [];
        foreach ($contacts as $contact) {
            $contactsDTO[] = PersonMapper::ContactToDto($contact);
        }

        return new JsonResponse([
            'items' => $contactsDTO,
            'paginationInfo' => $paginationInfo,
        ]);
    }

    /**
     * @Route("/persons/{personId}", methods={"DELETE"})
     */
    public function deleteContact(string $personId): Response
    {
        $this->personService->deleteContact($personId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/persons/{personId}", methods={"PUT"})
     */
    public function updateContact(string $personId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $contactName = $requestData['contactName'];
        $this->personService->updateContactName($personId, $contactName);

        return new JsonResponse('', Response::HTTP_OK);
    }

    /**
     * @Route("/persons/{personId}/groups", methods={"PUT"})
     */
    public function addContactGroup(string $personId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupId = $requestData['groupId'];
        $this->personService->addContactToGroup($personId, $groupId);

        return new JsonResponse('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/persons/{personId}/groups/{groupId}", methods={"PUT"})
     */
    public function removeContactGroup(string $personId, string $groupId): Response
    {
        $this->personService->removeContactFromGroup($personId, $groupId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/persons/{personId}/address", methods={"POST"})
     */
    public function addContactAddress(string $personId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupId = $requestData['groupId'];
    }

    /**
     * @Route("/persons/{personId}/address/{addressId}", methods={"DELETE"})
     */
    public function removeContactAddress(string $personId, string $addressId): Response
    {

    }

}

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
    public function createPerson(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $name = $requestData['name'];
        $personId = $this->personService->createPerson($name);

        return new JsonResponse($personId);
    }

    /**
     * @Route("/persons", methods={"GET"})
     */
    public function listActivePerson(Request $request): Response
    {
        $pageSizePar = $request->query->get('pageSize');
        $pagePar = $request->query->get('page');

        $pageSize = max(1, intval($pageSizePar));
        $page = min(self::MAX_ITEMS_PER_PAGE, intval($pagePar));

        $paginationInfo = $this->personService->calculatePaginationInfo($pageSize);
        $persons = $this->personService->listActivePersons($page, $pageSize);
        $personsDto = [];
        foreach ($persons as $person) {
            $personsDto[] = PersonMapper::PersonToDto($person);
        }

        return new JsonResponse([
            'items' => $personsDto,
            'paginationInfo' => $paginationInfo,
        ]);
    }

    /**
     * @Route("/persons/{personId}", methods={"DELETE"})
     */
    public function deletePerson(string $personId): Response
    {
        $this->personService->deletePerson($personId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/persons/{personId}", methods={"PUT"})
     */
    public function updatePerson(string $personId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $newName = $requestData['name'];
        $this->personService->updatePersonPersonalInfo($personId, $newName);

        return new Response(null, Response::HTTP_OK);
    }

    /**
     * @Route("/persons/{personId}/groups", methods={"PUT"})
     */
    public function addPersonGroup(string $personId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new JsonResponse('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupId = $requestData['groupId'];
        $this->personService->addPersonToGroup($personId, $groupId);

        return new Response(null, Response::HTTP_CREATED);
    }

    /**
     * @Route("/persons/{personId}/groups/{groupId}", methods={"PUT"})
     */
    public function removePersonGroup(string $personId, string $groupId): Response
    {
        $this->personService->removePersonFromGroup($personId, $groupId);

        return new Response(null,  Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/persons/{personId}/address", methods={"POST"})
     */
    public function addPersonAddress(string $personId, Request $request): Response
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
    public function removePersonAddress(string $personId, string $addressId): Response
    {

    }

}

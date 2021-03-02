<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\PersonsWebResponse;
use App\Service\PersonService;
use InvalidArgumentException;
use PageMapper;
use PersonMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
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
        $page = PageMapper::fromRequest($request);
        $paginationInfo = $this->personService->calculatePaginationInfo($page);
        $persons = $this->personService->listActivePersons($page);

        return new PersonsWebResponse($persons, $paginationInfo);
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
        try
        {
            $rawData = PersonMapper::rawDataFromRequest($request);
            $this->personService->updatePersonPersonalInfo($personId, $rawData['name']);
        }
        catch (InvalidArgumentException $ex)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

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

        return new Response(null, Response::HTTP_NO_CONTENT);
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

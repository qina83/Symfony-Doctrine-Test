<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\PersonsWebResponse;
use App\Response\InvalidSchemaWebResponse;
use App\Response\IdWebResponse;
use App\Service\PersonService;
use PageMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    private PersonService $personService;
    private JsonSchemaValidator $jsonValidator;

    /**
     * PersonController constructor.
     * @param PersonService $personService
     * @param JsonSchemaValidator $jsonValidator
     */
    public function __construct(PersonService $personService, JsonSchemaValidator $jsonValidator)
    {
        $this->personService = $personService;
        $this->jsonValidator = $jsonValidator;
    }


    /**
     * @Route("/persons", methods={"POST"})
     */
    public function createPerson(Request $request): Response
    {
        $parsedBody = (object)json_decode($request->getContent(), true);
        $errors = $this->jsonValidator->validate($parsedBody, $this->requestJsonSchemaForPerson());
        if (!empty($errors)) return new InvalidSchemaWebResponse($errors);

        $personId = $this->personService->createPerson($parsedBody->name);
        return new IdWebResponse($personId);
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
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/persons/{personId}", methods={"PUT"})
     */
    public function updatePerson(string $personId, Request $request): Response
    {
        $parsedBody = (object)json_decode($request->getContent(), true);
        $errors = $this->jsonValidator->validate($parsedBody, $this->requestJsonSchemaForPerson());
        if (!empty($errors)) return new InvalidSchemaWebResponse($errors);

        $this->personService->updatePersonPersonalInfo($personId, $parsedBody['name']);
        return new Response();
    }

    /**
     * @Route("/persons/{personId}/groups", methods={"PUT"})
     */
    public function addPersonGroup(string $personId, Request $request): Response
    {
        $parsedBody = (object)json_decode($request->getContent(), true);
        $errors = $this->jsonValidator->validate($parsedBody, $this->requestJsonSchemaForGroup());
        if (!empty($errors)) return new InvalidSchemaWebResponse($errors);

        $this->personService->addPersonToGroup($personId, $parsedBody->groupId);

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

    private function requestJsonSchemaForGroup(): array
    {
        return [
            'type' => 'object',
            'required' => ['groupId'],
            'properties' => [
                'groupId' => [
                    'type' => 'string',
                ],
            ]
        ];
    }

    private function requestJsonSchemaForPerson(): array
    {
        return [
            'type' => 'object',
            'required' => ['name'],
            'properties' => [
                'name' => [
                    'type' => 'string',
                ],
            ]
        ];
    }
}

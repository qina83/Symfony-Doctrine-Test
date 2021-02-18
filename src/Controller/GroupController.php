<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GroupServiceInterface;
use GroupMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    private GroupServiceInterface $groupService;

    /**
     * ContactController constructor.
     */
    public function __construct(GroupServiceInterface $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/group", methods={"POST"})
     */
    public function createGroup(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new Response('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupName = $requestData['groupName'];
        $groupId = $this->groupService->createGroup($groupName);

        return new JsonResponse($groupId);
    }

    /**
     * @Route("/groups", methods={"GET"})
     */
    public function listActiveGropus(): Response
    {
        $groups = $this->groupService->listActiveGroups();
        $groupsDTO = [];
        foreach ($groups as $group) {
            $groupsDTO[] = GroupMapper::GroupToDto($group);
        }

        return new JsonResponse($groupsDTO);
    }

    /**
     * @Route("/group/{groupId}", methods={"DELETE"})
     */
    public function deleteGroup(string $groupId): Response
    {
        $this->groupService->deleteGroup($groupId);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/group/{groupId}", methods={"PUT"})
     */
    public function updateGroup(string $groupId, Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new Response('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupName = $requestData['groupName'];
        $this->groupService->updateGroupName($groupId, $groupName);

        return new JsonResponse('', Response::HTTP_OK);
    }
}

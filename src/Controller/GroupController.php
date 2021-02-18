<?php


namespace App\Controller;


use App\Service\GroupService;
use GroupMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{

    private GroupService $groupService;

    /**
     * ContactController constructor.
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/group", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createGroup(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new Response('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupName = $requestData["groupName"];
        $groupId = $this->groupService->createGroup($groupName);

        return new JsonResponse($groupId);
    }

    /**
     * @Route("/groups", methods={"GET"})
     * @return Response
     */
    public function listActiveGropus(): Response{
        $groups = $this->groupService->listActiveGroups();
        $groupsDTO = [];
        foreach ($groups as $group) {
            $groupsDTO[] = GroupMapper::GroupToDto($group);
        }
        return new JsonResponse($groupsDTO);
    }

    /**
     * @Route("/group/{groupId}", methods={"DELETE"})
     * @param string $groupId
     * @return Response
     */
    public function deleteGroup(string $groupId): Response{

        $this->groupService->deleteGroup($groupId);
        return new JsonResponse('',Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/group/{groupId}", methods={"PUT"})
     * @param string $groupId
     * @param Request $request
     * @return Response
     */
    public function updateGroup(string $groupId, Request $request):Response{
        $requestData = json_decode($request->getContent(), true);
        if (!$requestData) {
            return new Response('Bad json string', Response::HTTP_BAD_REQUEST);
        }

        $groupName = $requestData["groupName"];
        $this->groupService->updateGroupName($groupId, $groupName);

        return new JsonResponse('',Response::HTTP_OK);
    }
}
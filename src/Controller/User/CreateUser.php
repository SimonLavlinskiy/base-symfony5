<?php


namespace App\Controller\User;


use App\Entity\User\User;
use App\Services\User\RegistrationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Webant\UserBundle\Model\UserManagerInterface;

class CreateUser
{
    private $serializer;
    private $registrationService;
    private $userManager;

    public function __construct(SerializerInterface $serializer,
        RegistrationService $registrationService,
        UserManagerInterface $userManager)
    {
        $this->serializer = $serializer;
        $this->registrationService = $registrationService;
        $this->userManager = $userManager;
    }


    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), $this->userManager->getClass(), 'json');
        $user->setEnabled(true);

        $this->registrationService->createUser($user, false);
        return new JsonResponse($this->serializer->serialize($user, 'json', ['groups' => ['GetObjUser']]),
            201, [], true);
    }

}
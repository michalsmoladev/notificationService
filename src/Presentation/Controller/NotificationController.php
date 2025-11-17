<?php

namespace App\Presentation\Controller;

use App\Application\Command\CreateNotification\CreateNotificationCommand;
use App\Application\DTO\NotificationDTO;
use App\Application\DTO\NotificationEmailDTO;
use App\Application\DTO\NotificationSmsDTO;
use App\Application\Query\GetNotificationById\GetNotificationByUserIdQuery;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[OA\Tag("Notification")]
class NotificationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessageBusInterface $queryBus,
    ) {
    }

    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            discriminator: new OA\Discriminator(
                propertyName: 'type',
                mapping: [
                    'email' => NotificationEmailDTO::class,
                    'sms' => NotificationSmsDTO::class,
                ],
            ),
            oneOf: [
                new OA\Schema(ref: new Model(type: NotificationEmailDTO::class, name: 'email')),
                new OA\Schema(ref: new Model(type: NotificationSmsDTO::class, name: 'sms')),
            ],
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Create notification',
        content: new OA\JsonContent(example: ''),
    )]
    #[Route(path: '/api/notification', name: 'app_api_notification_create', methods: ['POST'])]
    public function createAction(#[MapRequestPayload] NotificationDTO $notificationDTO): JsonResponse
    {
        $id = Uuid::v7();

        $this->commandBus->dispatch(
            message: new CreateNotificationCommand(
                id: $id,
                notificationDTO: $notificationDTO,
            )
        );

        return new JsonResponse(
            data: ['id' => $id],
            status: Response::HTTP_CREATED,
        );
    }

    #[Route(path: '/api/notification/{userId}', name: 'app_api_notification_read', methods: ['GET'])]
    public function getNotificationByUserIdAction(string $userId): JsonResponse
    {
        if (!Uuid::isValid($userId)) {
            return new JsonResponse(data: 'Invalid uuid', status: Response::HTTP_BAD_REQUEST);
        }

        $envelope = $this->queryBus->dispatch(
            message: new GetNotificationByUserIdQuery(userId: Uuid::fromString($userId)),
        );

        return $this->json(
            data: $envelope->last(HandledStamp::class)->getResult(),
            status: Response::HTTP_OK,
        );
    }
}

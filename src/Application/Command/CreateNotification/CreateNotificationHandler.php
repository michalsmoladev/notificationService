<?php

namespace App\Application\Command\CreateNotification;

use App\Application\Event\NotificationCreated;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use App\Domain\Entity\NotificationType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateNotificationHandler
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateNotificationCommand $command): void
    {
        $notification = Notification::create(
            id: $command->id,
            type: NotificationType::from($command->notificationDTO->type),
            senderEmail: $command->notificationDTO->senderEmail ?? null,
            senderNumber: $command->notificationDTO->senderNumber ?? null,
            recipientEmail: $command->notificationDTO->recipientEmail ?? null,
            recipientNumber: $command->notificationDTO->recipientNumber ?? null,
            subject: $command->notificationDTO->subject,
            message: $command->notificationDTO->message,
            isDelayed: $command->notificationDTO->isDelayed,
        );

        $this->notificationRepository->save($notification);

        $this->logger->info(
            '[CreateNotification] Notification created',
            [
                'id' => $notification->getId(),
            ]
        );

        $this->eventDispatcher->dispatch(
            event: new NotificationCreated($notification->getId()),
        );
    }
}

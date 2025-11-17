<?php

namespace App\Application\Command\SendNotification;

use App\Application\Exception\AllProvidersFailedException;
use App\Application\Strategy\NotificationStrategy;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationHandler
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly NotificationStrategy $notificationStrategy,
    ) {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        /** @var Notification $notification */
        $notification = $this->notificationRepository->findById($command->notificationId);

        try {
            $this->notificationStrategy->process($notification);

            $notification->markAsSent();
            $notification->setSentAt(new \DateTimeImmutable());
        } catch (AllProvidersFailedException $exception) {
            $notification->markAsError();

           throw $exception;
        }

        $this->notificationRepository->save($notification);
    }
}

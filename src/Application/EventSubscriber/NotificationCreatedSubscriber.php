<?php

namespace App\Application\EventSubscriber;

use App\Application\Command\SendNotification\SendNotificationCommand;
use App\Application\Event\NotificationCreated;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class NotificationCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationCreated::class => 'onNotificationCreated',
        ];
    }

    public function onNotificationCreated(NotificationCreated $event): void
    {
        $stamps = [];

        /** @var Notification $notification */
        $notification = $this->notificationRepository->findById($event->notificationId);


        if ($notification->isDelayed()) {
            $stamps[] = new TransportNamesStamp(['send_notification_throttled']);
        }

        $this->commandBus->dispatch(new SendNotificationCommand(notificationId: $event->notificationId), $stamps);
    }
}

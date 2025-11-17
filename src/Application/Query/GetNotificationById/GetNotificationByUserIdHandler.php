<?php

namespace App\Application\Query\GetNotificationById;

use App\Application\DTO\NotificationDTO;
use App\Application\DTO\NotificationEmailDTO;
use App\Application\Query\GetNotificationById\DTO\GetNotificationDTO;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use App\Domain\Entity\NotificationType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class GetNotificationByUserIdHandler
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function __invoke(GetNotificationByUserIdQuery $query): Collection
    {
        $notificationCollection = $this->notificationRepository->findAllByUserId(Uuid::fromString($query->userId));

        $notificationsCollectionDTO = new ArrayCollection();

        foreach ($notificationCollection as $notification) {
            $notificationsCollectionDTO->add(new GetNotificationDTO(
                recipientEmail: $notification->getRecipientEmail(),
                sentAt: $notification->getSentAt(),
            ));
        }

        return $notificationsCollectionDTO;
    }
}

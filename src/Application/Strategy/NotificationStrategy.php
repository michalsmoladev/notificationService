<?php

namespace App\Application\Strategy;

use App\Application\Service\NotificationService;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NotificationStrategy
{
    public function __construct(
        /** @var iterable<NotificationStrategyInterface> */
        #[AutowireIterator('app.notification_strategy')]
        private readonly iterable $notifications,
        private readonly LoggerInterface $logger,
        private readonly NotificationService $notificationService,
        private readonly NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function process(Notification $notification): void
    {
        if (!$this->notificationService->isEnabled($notification->getType())) {
            $this->logger->info(sprintf('Notification type: %s is not enabled', $notification->getType()));
            $notification->markAsSkipped();
            $this->notificationRepository->save($notification);

            return;
        }

        foreach ($this->notifications as $strategy) {
            if ($strategy->supports($notification->getType())) {
                $strategy->process($notification);
            }
        }
    }
}

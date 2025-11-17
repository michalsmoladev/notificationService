<?php

namespace App\Application\Strategy;

use App\Application\Provider\SmsProviderInterface;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationType;

class SmsNotificationStrategy implements NotificationStrategyInterface
{
    public function __construct(
        private readonly SmsProviderInterface $smsProvider,
    ) {
    }

    public function supports(string $type): bool
    {
        return $type === NotificationType::SMS->value || $type === NotificationType::MULTI->value;
    }

    public function process(Notification $notification): void
    {
        $this->smsProvider->send($notification);
    }
}

<?php

namespace App\Application\Strategy;

use App\Application\Provider\Email\FailoverEmailSender;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationType;

class EmailNotificationStrategy implements NotificationStrategyInterface
{
    public function __construct(
        private readonly FailoverEmailSender $emailSender,
    ) {
    }

    public function supports(string $type): bool
    {
        return $type === NotificationType::EMAIL->value || $type === NotificationType::MULTI->value;
    }

    public function process(Notification $notification): void
    {
        $this->emailSender->send($notification);
    }
}

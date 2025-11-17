<?php

namespace App\Application\Service;

class NotificationService
{
    public function __construct(
        private readonly array $notificationsConfiguration,
    ) {
    }

    public function isEnabled(string $notificationType): bool
    {
        return (bool) $this->notificationsConfiguration[$notificationType] ?? false;
    }
}

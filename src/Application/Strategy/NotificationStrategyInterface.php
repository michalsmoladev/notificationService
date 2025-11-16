<?php

namespace App\Application\Strategy;

use App\Domain\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notification_strategy')]
interface NotificationStrategyInterface
{
    public function supports(string $type): bool;
    public function process(Notification $notification): void;
}

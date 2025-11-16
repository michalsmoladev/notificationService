<?php

namespace App\Application\Provider;

use App\Domain\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.sms_providers')]
interface SmsProviderInterface
{
    public function getName(): string;
    public function send(Notification $notification): void;
}

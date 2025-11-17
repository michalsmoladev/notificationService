<?php

namespace App\Application\Provider;

use App\Domain\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.email_providers')]
interface EmailProviderInterface
{
    public function getName(): string;
    public function send(Notification $notification): void;
}

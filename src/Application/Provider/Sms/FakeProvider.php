<?php

namespace App\Application\Provider\Sms;

use App\Application\Provider\SmsProviderInterface;
use App\Domain\Entity\Notification;
use Psr\Log\LoggerInterface;

class FakeProvider implements SmsProviderInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getName(): string
    {
        return FakeProvider::class;
    }

    public function send(Notification $notification): void
    {
        $this->logger->info(sprintf(
            '[FAKE SMS] to: %s | message: %s',
            $notification->getRecipientNumber(),
            sprintf("%s: %s", $notification->getStatus(), $notification->getMessage()),
        ));
    }
}

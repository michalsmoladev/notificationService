<?php

namespace App\Application\Provider\Email;

use App\Application\Provider\EmailProviderInterface;
use App\Domain\Entity\Notification;
use Psr\Log\LoggerInterface;

class FakeProvider implements EmailProviderInterface
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
            '[FAKE EMAIL] to: %s | message: %s',
            $notification->getRecipientEmail(),
            sprintf("%s: %s", $notification->getStatus(), $notification->getMessage()),
        ));
    }
}

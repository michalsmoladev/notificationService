<?php

namespace App\Application\Provider\Email;

use App\Application\Exception\AllProvidersFailedException;
use App\Application\Provider\EmailProviderInterface;
use App\Domain\Entity\Notification;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class FailoverEmailSender implements EmailProviderInterface
{
    public function __construct(
        #[AutowireIterator('app.email_providers')]
        private readonly iterable $providers,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getName(): string
    {
        return FailoverEmailSender::class;
    }

    public function send(Notification $notification): void
    {
        $errors = [];

        /** @var EmailProviderInterface $provider */
        foreach ($this->providers as $provider) {
            try {
                $this->logger->info(sprintf(
                    'Sending Email using provider "%s"',
                    $provider->getName()
                ));

                $provider->send($notification);

                return;
            } catch (\Throwable $exception) {
                $this->logger->error(sprintf(
                    'Email provider "%s" failed: %s',
                    $provider->getName(),
                    $exception->getMessage()
                ));

                $errors[] = sprintf(
                    '%s: %s',
                    $provider::class,
                    $exception->getMessage(),
                );
            }
        }

        throw new AllProvidersFailedException($errors);
    }
}

<?php

namespace App\Application\Provider\Sms;

use App\Application\Exception\AllProvidersFailedException;
use App\Application\Provider\SmsProviderInterface;
use App\Domain\Entity\Notification;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class FailoverSmsSender implements SmsProviderInterface
{
    public function __construct(
        #[AutowireIterator('app.sms_providers')]
        private readonly iterable $providers,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getName(): string
    {
        return FailoverSmsSender::class;
    }

    public function send(Notification $notification): void
    {
        $errors = [];

        foreach ($this->providers as $provider) {
            try {
                $this->logger->info(sprintf(
                    'Sending SMS using provider "%s"',
                    $provider->getName()
                ));

                $provider->send($notification);

                return;
            } catch (\Throwable $exception) {
                $this->logger->error(sprintf(
                    'SMS provider "%s" failed: %s',
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

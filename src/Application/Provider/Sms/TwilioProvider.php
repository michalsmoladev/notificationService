<?php

namespace App\Application\Provider\Sms;

use App\Application\Provider\SmsProviderInterface;
use App\Domain\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportFactoryInterface;
use Symfony\Component\Notifier\Transport\TransportInterface;

#[AutoconfigureTag('app.sms_providers', attributes: ['priority' => 100])]
class TwilioProvider implements SmsProviderInterface
{
    private TransportInterface $transport;

    public function __construct(
        #[Autowire(service: 'notifier.transport_factory.twilio')]
        private readonly TransportFactoryInterface $transportFactory,

        #[Autowire(env: 'TWILIO_DSN')]
        private string $dsn,
    ) {
        $this->transport = $this->transportFactory->create(new Dsn($this->dsn));
    }

    public function getName(): string
    {
        return TwilioProvider::class;
    }

    public function send(Notification $notification): void
    {
        $sms = new SmsMessage(
            phone: $notification->getRecipientNumber(),
            subject: sprintf("%s: %s", $notification->getSubject(), $notification->getMessage()),
        );

        $this->transport->send($sms);
    }
}

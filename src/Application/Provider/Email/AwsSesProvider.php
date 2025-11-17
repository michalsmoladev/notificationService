<?php

namespace App\Application\Provider\Email;

use App\Application\Provider\EmailProviderInterface;
use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;

#[AutoconfigureTag('app.email_providers', attributes: ['priority' => 100])]
class AwsSesProvider implements EmailProviderInterface
{
    private MailerInterface $mailer;

    public function __construct(
        #[Autowire(env: 'AWS_SES_MAILER_DSN')]
        private string $dsn,
        private readonly LoggerInterface $logger,
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
        $this->mailer = new Mailer(
            transport: Transport::fromDsn($this->dsn),
        );
    }

    public function getName(): string
    {
        return AwsSesProvider::class;
    }

    public function send(Notification $notification): void
    {
        $email = new TemplatedEmail()
            ->from($notification->getSenderEmail())
            ->to($notification->getRecipientEmail())
            ->subject($notification->getSubject())
            ->text($notification->getMessage())
        ;

        try {
            $this->mailer->send($email);

            $this->logger->info('[EmailNotificationStrategy] Notification sent.', [
                'notification_id' => $notification->getId()->toString(),
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('[EmailNotificationStrategy] Failed to send notification', [
                'notification_id' => $notification->getId()->toString(),
                'exceptionMessage' => $e->getMessage(),
            ]);
        }
    }
}

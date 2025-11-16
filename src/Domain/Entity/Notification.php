<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Notification
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', length: 36, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 50, enumType: NotificationType::class)]
    private NotificationType $type;

    #[ORM\Column(type: 'string', length: 50, enumType: NotificationStatus::class)]
    private NotificationStatus $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\Column(type: 'string', length: 128, nullable: true)]
        private ?string $senderEmail,

        #[ORM\Column(type: 'string', length: 128, nullable: true)]
        private ?string $senderNumber,

        #[ORM\Column(type: 'string', length: 128, nullable: true)]
        private ?string $recipientEmail,

        #[ORM\Column(type: 'string', length: 128, nullable: true)]
        private ?string $recipientNumber,

        #[ORM\Column(type: 'string', length: 128)]
        private string $subject,

        #[ORM\Column(type: 'json', length: 255)]
        private string $message,

        #[ORM\Column(type: 'boolean')]
        private bool $isDelayed = false,
    ) {
    }

    public static function create(
        Uuid $id,
        NotificationType $type,
        ?string $senderEmail,
        ?string $senderNumber,
        ?string $recipientEmail,
        ?string $recipientNumber,
        string $subject,
        string $message,
        bool $isDelayed,
    ): self
    {
        $notification = new self(
            senderEmail: $senderEmail,
            senderNumber: $senderNumber,
            recipientEmail: $recipientEmail,
            recipientNumber: $recipientNumber,
            subject: $subject,
            message: $message,
        );

        $notification->id = $id;

        $notification->markAsDraft();
        $notification->markTypeAs($type);

        if ($isDelayed) {
            $notification->markAsDelayed();
        }

        return $notification;
    }

    protected function markTypeAs(NotificationType $type): void
    {
        match ($type) {
            NotificationType::EMAIL => $this->markAsEmail(),
            NotificationType::SMS => $this->markAsSms(),
            NotificationType::MULTI => $this->markAsMulti()
        };
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function getSenderNumber(): ?string
    {
        return $this->senderNumber;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function getRecipientNumber(): ?string
    {
        return $this->recipientNumber;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function markAsEmail(): void
    {
        $this->type = NotificationType::EMAIL;
    }

    public function markAsSms(): void
    {
        $this->type = NotificationType::SMS;
    }

    public function markAsMulti(): void
    {
        $this->type = NotificationType::MULTI;
    }

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function isDelayed(): bool
    {
        return $this->isDelayed;
    }

    public function markAsDelayed(): void
    {
        $this->isDelayed = true;
    }

    public function markAsDraft(): void
    {
        $this->status = NotificationStatus::DRAFT;
    }

    public function markAsSent(): void
    {
        $this->status = NotificationStatus::SENT;
    }

    public function markAsError(): void
    {
        $this->status = NotificationStatus::ERROR;
    }

    public function markAsSkipped(): void
    {
        $this->status = NotificationStatus::SKIPPED;
    }

    #[ORM\PrePersist]
    public function preCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}

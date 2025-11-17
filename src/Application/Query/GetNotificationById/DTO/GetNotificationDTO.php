<?php

namespace App\Application\Query\GetNotificationById\DTO;

class GetNotificationDTO
{
    public function __construct(
        public string $recipientEmail,
        public ?\DateTimeImmutable $sentAt,
    ) {
    }
}

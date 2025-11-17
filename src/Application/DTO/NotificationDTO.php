<?php

namespace App\Application\DTO;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;
use Symfony\Component\Uid\Uuid;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'email' => NotificationEmailDTO::class,
        'sms' => NotificationSmsDTO::class,
        'multi' => NotificationMultiDTO::class,
    ],
)]
abstract class NotificationDTO
{
    public function __construct(
        public string $subject,
        public string $message,
        public bool $isDelayed,
        public string $userId,
    ) {
    }
}

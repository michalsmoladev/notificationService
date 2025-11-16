<?php

namespace App\Application\DTO;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

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
}

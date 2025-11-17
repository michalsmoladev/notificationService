<?php

namespace App\Application\Query\GetNotificationById;

use Symfony\Component\Uid\Uuid;

class GetNotificationByUserIdQuery
{
    public function __construct(
        public Uuid $userId,
    ) {
    }
}

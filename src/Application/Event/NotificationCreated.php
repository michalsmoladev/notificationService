<?php

namespace App\Application\Event;

use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

class NotificationCreated extends Event
{
    public function __construct(
        public Uuid $notificationId,
    ) {
    }
}

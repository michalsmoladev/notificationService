<?php

namespace App\Application\Command\SendNotification;

use Symfony\Component\Uid\Uuid;

class SendNotificationCommand
{
    public function __construct(
        public Uuid $notificationId,
    ) {
    }
}

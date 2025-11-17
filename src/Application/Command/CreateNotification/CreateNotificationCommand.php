<?php

namespace App\Application\Command\CreateNotification;

use App\Application\DTO\NotificationDTO;
use Symfony\Component\Uid\Uuid;

class CreateNotificationCommand
{
    public function __construct(
        public Uuid $id,
        public NotificationDTO $notificationDTO,
    ) {
    }
}

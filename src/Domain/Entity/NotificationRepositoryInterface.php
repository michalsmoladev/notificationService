<?php

namespace App\Domain\Entity;

use Symfony\Component\Uid\Uuid;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): void;
    public function findById(Uuid $id): ?Notification;
}

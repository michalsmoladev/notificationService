<?php

namespace App\Domain\Entity;

enum NotificationType: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case MULTI = 'multi';
}

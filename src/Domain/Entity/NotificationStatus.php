<?php

namespace App\Domain\Entity;

enum NotificationStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ERROR = 'error';
    case SKIPPED = 'skipped';
}

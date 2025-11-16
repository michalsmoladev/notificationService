<?php

namespace App\Application\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class NotificationEmailDTO extends NotificationDTO
{
    public string $type = 'email';

    public function __construct(
        #[OA\Property(example: 'fromExample@example.com')]
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $senderEmail,

        #[OA\Property(example: 'recipientExample@example.com')]
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $recipientEmail,

        #[OA\Property(example: 'Example subject')]
        #[Assert\NotBlank]
        public string $subject,

        #[OA\Property(example: 'Example payload')]
        #[Assert\NotBlank]
        public string $message,

        #[OA\Property(example: false)]
        public bool $isDelayed,
    ) {
    }
}

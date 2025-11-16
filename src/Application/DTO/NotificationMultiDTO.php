<?php

namespace App\Application\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class NotificationMultiDTO extends NotificationDTO
{
    public string $type = 'multi';

    public function __construct(
        #[OA\Property(example: '+48123321123')]
        #[Assert\NotBlank]
        public string $senderNumber,

        #[OA\Property(example: 'exampleSender@example.com')]
        #[Assert\NotBlank]
        public string $senderEmail,

        #[OA\Property(example: '+48123456789')]
        #[Assert\NotBlank]
        public string $recipientNumber,

        #[OA\Property(example: 'exampleRecipient@example.com')]
        #[Assert\NotBlank]
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

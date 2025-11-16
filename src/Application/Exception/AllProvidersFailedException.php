<?php

namespace App\Application\Exception;

class AllProvidersFailedException extends \RuntimeException
{
    public function __construct(
        array $errors
    ) {
        $message = 'All notification providers failed.';

        if (!empty($errors)) {
            $message .= ' Reasons: ' . implode(' | ', $errors);
        }

        parent::__construct($message);
    }
}

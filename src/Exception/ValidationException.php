<?php
namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Exception;

class ValidationException extends Exception
{
    private array $errors;

    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function fromConstraintViolations(ConstraintViolationListInterface $violations): self
    {
        $errorMessages = [];
        foreach ($violations as $violation) {
            $errorMessages[] = $violation->getMessage();
        }

        return new self('Validation failed', $errorMessages);
    }
}

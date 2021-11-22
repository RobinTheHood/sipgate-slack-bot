<?php

declare(strict_types=1);

namespace App\Classes\Sipgate;

class Contact
{
    private $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    public function getId(): string
    {
        return $this->rawData['id'];
    }

    public function getName(): string
    {
        return $this->rawData['name'];
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }
}

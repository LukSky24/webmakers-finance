<?php

namespace App\Core\Domain\ValueObject;

class ObjectReference
{
    public function __construct(
        private readonly string $objectType,
        private readonly int $objectId
    ) {}

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function equals(ObjectReference $other): bool
    {
        return $this->objectType === $other->objectType
            && $this->objectId === $other->objectId;
    }
}

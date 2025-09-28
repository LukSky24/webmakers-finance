<?php

namespace App\Core\Domain\Entity;

use App\Core\Domain\ValueObject\ObjectReference;
use App\Core\Domain\ValueObject\WarningType;
use App\Shared\Domain\Entity\AbstractEntity;
use App\Shared\Domain\ValueObject\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'warnings')]
class Warning extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $objectType;

    #[ORM\Column(type: 'integer')]
    private int $objectId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $warningType;

    #[ORM\Embedded(class: Timestamp::class)]
    protected Timestamp $timestamp;

    public function __construct(
        ObjectReference $objectReference,
        WarningType $warningType
    ) {
        parent::__construct();
        $this->objectType = $objectReference->getObjectType();
        $this->objectId = $objectReference->getObjectId();
        $this->warningType = $warningType->getValue();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getObjectReference(): ObjectReference
    {
        return new ObjectReference($this->objectType, $this->objectId);
    }

    public function getWarningType(): WarningType
    {
        return WarningType::from($this->warningType);
    }

    public function update(): void
    {
        $this->updateTimestamp();
    }
}

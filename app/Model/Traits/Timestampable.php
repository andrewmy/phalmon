<?php

declare(strict_types=1);

namespace App\Model\Traits;

trait Timestampable
{
    /** @var \DateTime */
    public $createdAt;

    /** @var \DateTime */
    public $updatedAt;

    public function beforeCreate(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function beforeUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Traits\Timestampable;
use Phalcon\Mvc\MongoCollection;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class LogEntry extends MongoCollection
{
    use Timestampable;

    const ACTION_VIEW_MESSAGES = 'view_messages';

    /** @var string */
    public $userId;

    /** @var string */
    public $action;

    public function validation()
    {
        $validator = new Validation();

        $validator->add('userId', new Validator\PresenceOf());

        $validator->add('action', new Validator\PresenceOf());
        $validator->add('action', new Validator\InclusionIn([
            'domain' => [self::ACTION_VIEW_MESSAGES],
        ]));

        return $this->validate($validator);
    }
}

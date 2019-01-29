<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Traits\Timestampable;
use Phalcon\Mvc\MongoCollection;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Message extends MongoCollection
{
    use Timestampable;

    /** @var string */
    public $userId;

    /** @var string */
    public $content;

    public function validation()
    {
        $validator = new Validation();

        $validator->add('userId', new Validator\PresenceOf());
        $validator->add('userId', new Validator\Callback([
            'callback' => function (self $value) {
                if (!User::findById($value->userId)) {
                    return false;
                }

                return true;
            },
            'message' => 'Unknown user',
        ]));

        $validator->add('content', new Validator\PresenceOf());

        return $this->validate($validator);
    }
}

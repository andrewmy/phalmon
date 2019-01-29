<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Traits\Timestampable;
use Phalcon\Mvc\MongoCollection;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class User extends MongoCollection
{
    use Timestampable;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    public function validation()
    {
        $validator = new Validation();

        $validator->add('username', new Validator\PresenceOf());
        $validator->add('username', new Validator\Uniqueness());

        $validator->add('password', new Validator\PresenceOf());

        return $this->validate($validator);
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use Mikemirten\Component\JsonApi\Document\ErrorObject;
use Mikemirten\Component\JsonApi\Document\NoDataDocument;
use Phalcon\Http\Response;

class Utility
{
    public static function returnError(
        Response $response, $code, string $title, ?string $detail = null
    ): void {
        $response->setStatusCode($code, $title);

        $error = new ErrorObject();
        $error->setCode((string) $code);
        $error->setTitle($title);
        if ($detail) {
            $error->setDetail($detail);
        }
        $document = new NoDataDocument();
        $document->addError($error);

        $response->setJsonContent($document->toArray());
    }

    public static function returnNoContent(
        Response $response, $code, string $title
    ): void {
        $response->setStatusCode($code, $title);
        $document = new NoDataDocument();
        $response->setJsonContent($document->toArray());
    }

    public static function mongoDateFormat(array $date, string $format): ?string
    {
        if (!isset($date['date'])) {
            return null;
        }

        return (new \DateTime($date['date']))->format($format);
    }
}

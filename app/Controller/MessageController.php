<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\LogEntry;
use App\Model\Message;
use App\Service\Utility;
use Dmkit\Phalcon\Auth\Middleware\Micro;
use Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\NativeArray;
use Phalcon\Queue\Beanstalk;

/**
 * @property Micro     $auth
 * @property Beanstalk $queue
 */
class MessageController extends Controller
{
    const PER_PAGE = 10;

    public function list(): array
    {
        $userId = $this->auth->id();
        $currentPage = \max(1, $this->request->getQuery('page', 'int'));

        $logEntry = new LogEntry();
        $logEntry->userId = $userId;
        $logEntry->action = LogEntry::ACTION_VIEW_MESSAGES;
        $logEntry->save();

        $meta = ['request_count' => LogEntry::count(['userId' => $userId])];

        $messages = Message::find([
            'conditions' => ['userId' => $userId],
            'sort' => ['createdAt' => -1],
        ]);
        $paginator = new NativeArray([
            'data' => $messages,
            'limit' => self::PER_PAGE,
            'page' => $currentPage,
        ]);
        $page = $paginator->getPaginate();

        if ($page->before && $page->before !== $page->current) {
            $meta['prev'] = '/api/messages?page='.$page->before;
        }
        if ($page->next && $page->next !== $page->current) {
            $meta['next'] = '/api/messages?page='.$page->next;
        }

        $document = new ResourceCollectionDocument($meta);

        /** @var Message $message */
        foreach ($page->items as $message) {
            $document->addResource(
                new ResourceObject(
                    (string) $message->getId(),
                    'Message',
                    [
                        'user' => $userId,
                        'content' => $message->content,
                        'createdAt' => Utility::mongoDateFormat(
                            $message->createdAt, 'c'
                        ),
                    ]
                )
            );
        }

        return $document->toArray();
    }

    /**
     * @throws \Phalcon\Mvc\Collection\Exception
     *
     * @return array|Response
     */
    public function create()
    {
        $request = $this->request->getJsonRawBody(true);
        $content = $request['content'] ?? null;

        if ($content) {
            $this->queue->put(['createMessage' => [
                'userId' => $this->auth->id(),
                'content' => $content,
            ]]);

            $response = new Response();
            Utility::returnNoContent($response, 201, 'Created');

            return $response;
        }

        $response = new Response();
        Utility::returnError($response, 422, 'No content');

        return $response;
    }
}

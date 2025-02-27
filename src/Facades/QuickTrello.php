<?php

namespace QuickTrello\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBoards()
 * @method static array getBoard(string $boardId)
 * @method static array getLists(string $boardId)
 * @method static array getCards(string $listId)
 * @method static array createCard(string $listId, string $name, array $options = [])
 * @method static array updateCard(string $cardId, array $data)
 * @method static array moveCard(string $cardId, string $listId)
 * @method static array addComment(string $cardId, string $text)
 * @method static array createWebhook(string $callbackUrl, string $idModel, string $description = '')
 * @method static array deleteWebhook(string $webhookId)
 * 
 * @see \QuickTrello\Services\TrelloService
 */
class QuickTrello extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quicktrello';
    }
}
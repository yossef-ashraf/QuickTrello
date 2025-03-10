<?php

namespace QuickTrello\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBoards()
 * @method static array getBoard(string $boardId)
 * @method static array getLists(string $boardId)
 * @method static array createList(string $boardId, string $name, array $options = [])
 * @method static array updateList(string $listId, array $data)
 * @method static array archiveList(string $listId, bool $archived = true)
 * @method static array getCards(string $listId)
 * @method static array createCard(string $listId, string $name, array $options = [])
 * @method static array updateCard(string $cardId, array $data)
 * @method static array moveCard(string $cardId, string $listId)
 * @method static array addComment(string $cardId, string $text)
 * @method static array addLabel(string $cardId, string $labelId)
 * @method static array removeLabel(string $cardId, string $labelId)
 * @method static array getBoardMembers(string $boardId)
 * @method static array getOrganizationMembers(string $orgId)
 * @method static array assignMemberToCard(string $cardId, string $memberId)
 * @method static array removeMemberFromCard(string $cardId, string $memberId)
 * @method static array getCardMembers(string $cardId)
 * @method static array createWebhook(string $callbackUrl, string $idModel, string $description = '')
 * @method static array deleteWebhook(string $webhookId)
 * @method static array getWebhooks()
 * @method static array addDueDate(string $cardId, string $dueDate)
 * @method static array addChecklist(string $cardId, string $name)
 * @method static array addChecklistItem(string $checklistId, string $name)
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
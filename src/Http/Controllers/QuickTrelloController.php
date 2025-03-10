<?php
namespace QuickTrello\Http\Controllers;

use QuickTrello\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use QuickTrello\Facades\QuickTrello;
use QuickTrello\Exceptions\TrelloException;
use Illuminate\Routing\Controller;

class QuickTrelloController extends Controller
{
    use ResponseTrait;

    /**
     * Get all boards for the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function getBoards()
    {
        try {
            $boards = QuickTrello::getBoards();
            return $this->sendResponse(200, 'Boards retrieved successfully', null, $boards);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get a specific board by ID
     *
     * @param string $boardId
     * @return \Illuminate\Http\Response
     */
    public function getBoard($boardId)
    {
        try {
            $board = QuickTrello::getBoard($boardId);
            return $this->sendResponse(200, 'Board retrieved successfully', null, $board);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get board members
     *
     * @param string $boardId
     * @return \Illuminate\Http\Response
     */
    public function getBoardMembers($boardId)
    {
        try {
            $members = QuickTrello::getBoardMembers($boardId);
            return $this->sendResponse(200, 'Board members retrieved successfully', null, $members);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get organization members
     *
     * @param string $orgId
     * @return \Illuminate\Http\Response
     */
    public function getOrganizationMembers($orgId)
    {
        try {
            $members = QuickTrello::getOrganizationMembers($orgId);
            return $this->sendResponse(200, 'Organization members retrieved successfully', null, $members);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get lists for a board
     *
     * @param string $boardId
     * @return \Illuminate\Http\Response
     */
    public function getLists($boardId)
    {
        try {
            $lists = QuickTrello::getLists($boardId);
            return $this->sendResponse(200, 'Lists retrieved successfully', null, $lists);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Create a new list on a board
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createList(Request $request)
    {
        try {
            $list = QuickTrello::createList(
                $request->input('board_id'),
                $request->input('name'),
                $request->input('options', [])
            );
            return $this->sendResponse(200, 'List created successfully', null, $list);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Update a list
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateList(Request $request)
    {
        try {
            $list = QuickTrello::updateList(
                $request->input('list_id'),
                $request->input('data', [])
            );
            return $this->sendResponse(200, 'List updated successfully', null, $list);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Archive or unarchive a list
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function archiveList(Request $request)
    {
        try {
            $list = QuickTrello::archiveList(
                $request->input('list_id'),
                $request->input('archived', true)
            );
            $action = $request->input('archived', true) ? 'archived' : 'unarchived';
            return $this->sendResponse(200, "List {$action} successfully", null, $list);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get cards for a list
     *
     * @param string $listId
     * @return \Illuminate\Http\Response
     */
    public function getCards($listId)
    {
        try {
            $cards = QuickTrello::getCards($listId);
            return $this->sendResponse(200, 'Cards retrieved successfully', null, $cards);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Create a new card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createCard(Request $request)
    {
        try {
            $card = QuickTrello::createCard(
                $request->input('list_id'),
                $request->input('title'),
                [
                    'desc' => $request->input('desc', ''),
                    'due' => $request->input('due', null),
                    'pos' => $request->input('position', 'bottom'),
                    'idLabels' => $request->input('labels', []),
                    'idMembers' => $request->input('members', []),
                ]
            );
            return $this->sendResponse(200, 'Card created successfully', null, $card);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Update a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateCard(Request $request)
    {
        try {
            $card = QuickTrello::updateCard(
                $request->input('card_id'),
                $request->input('data', [])
            );
            return $this->sendResponse(200, 'Card updated successfully', null, $card);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Move a card to a different list
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function moveCard(Request $request)
    {
        try {
            $card = QuickTrello::moveCard(
                $request->input('card_id'),
                $request->input('list_id')
            );
            return $this->sendResponse(200, 'Card moved successfully', null, $card);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Add a comment to a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request)
    {
        try {
            $comment = QuickTrello::addComment(
                $request->input('card_id'),
                $request->input('comment')
            );
            return $this->sendResponse(200, 'Comment added successfully', null, $comment);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Add a label to a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addLabel(Request $request)
    {
        try {
            $result = QuickTrello::addLabel(
                $request->input('card_id'),
                $request->input('label_id')
            );
            return $this->sendResponse(200, 'Label added successfully', null, $result);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Remove a label from a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeLabel(Request $request)
    {
        try {
            $result = QuickTrello::removeLabel(
                $request->input('card_id'),
                $request->input('label_id')
            );
            return $this->sendResponse(200, 'Label removed successfully', null, $result);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Assign a member to a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignMemberToCard(Request $request)
    {
        try {
            $result = QuickTrello::assignMemberToCard(
                $request->input('card_id'),
                $request->input('member_id')
            );
            return $this->sendResponse(200, 'Member assigned successfully', null, $result);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Remove a member from a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeMemberFromCard(Request $request)
    {
        try {
            $result = QuickTrello::removeMemberFromCard(
                $request->input('card_id'),
                $request->input('member_id')
            );
            return $this->sendResponse(200, 'Member removed successfully', null, $result);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get members assigned to a card
     *
     * @param string $cardId
     * @return \Illuminate\Http\Response
     */
    public function getCardMembers($cardId)
    {
        try {
            $members = QuickTrello::getCardMembers($cardId);
            return $this->sendResponse(200, 'Card members retrieved successfully', null, $members);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Add a due date to a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addDueDate(Request $request)
    {
        try {
            $card = QuickTrello::addDueDate(
                $request->input('card_id'),
                $request->input('due_date')
            );
            return $this->sendResponse(200, 'Due date added successfully', null, $card);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Add a checklist to a card
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addChecklist(Request $request)
    {
        try {
            $checklist = QuickTrello::addChecklist(
                $request->input('card_id'),
                $request->input('name')
            );
            return $this->sendResponse(200, 'Checklist added successfully', null, $checklist);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Add a checklist item
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addChecklistItem(Request $request)
    {
        try {
            $item = QuickTrello::addChecklistItem(
                $request->input('checklist_id'),
                $request->input('name')
            );
            return $this->sendResponse(200, 'Checklist item added successfully', null, $item);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Create a webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createWebhook(Request $request)
    {
        try {
            $webhook = QuickTrello::createWebhook(
                $request->input('callback_url'),
                $request->input('model_id'),
                $request->input('description', '')
            );
            return $this->sendResponse(200, 'Webhook created successfully', null, $webhook);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Delete a webhook
     *
     * @param string $webhookId
     * @return \Illuminate\Http\Response
     */
    public function deleteWebhook($webhookId)
    {
        try {
            $result = QuickTrello::deleteWebhook($webhookId);
            return $this->sendResponse(200, 'Webhook deleted successfully', null, $result);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
    
    /**
     * Get all webhooks
     *
     * @return \Illuminate\Http\Response
     */
    public function getWebhooks()
    {
        try {
            $webhooks = QuickTrello::getWebhooks();
            return $this->sendResponse(200, 'Webhooks retrieved successfully', null, $webhooks);
        } catch (TrelloException $e) {
            return $this->sendResponse($e->getCode(), $e->getMessage(), $e->getMessage());
        }
    }
}
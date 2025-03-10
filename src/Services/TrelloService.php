<?php

namespace QuickTrello\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use QuickTrello\Exceptions\TrelloException;

class TrelloService
{
    protected $apiKey;
    protected $token;
    protected $baseUrl;

    public function __construct($apiKey, $token, $baseUrl = 'https://api.trello.com/1')
    {
        $this->apiKey = $apiKey;
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get all boards for the authenticated user
     *
     * @return array
     */
    public function getBoards()
    {
        return $this->request('GET', '/members/me/boards');
    }

    /**
     * Get a specific board by ID
     *
     * @param string $boardId
     * @return array
     */
    public function getBoard(string $boardId)
    {
        return $this->request('GET', "/boards/{$boardId}");
    }

    /**
     * Get lists for a board
     *
     * @param string $boardId
     * @return array
     */
    public function getLists(string $boardId)
    {
        return $this->request('GET', "/boards/{$boardId}/lists");
    }

    /**
     * Create a new list on a board
     * 
     * @param string $boardId
     * @param string $name
     * @param array $options
     * @return array
     */
    public function createList(string $boardId, string $name, array $options = [])
    {
        $data = array_merge([
            'idBoard' => $boardId,
            'name' => $name,
        ], $options);

        return $this->request('POST', '/lists', $data);
    }

    /**
     * Update a list
     * 
     * @param string $listId
     * @param array $data
     * @return array
     */
    public function updateList(string $listId, array $data)
    {
        return $this->request('PUT', "/lists/{$listId}", $data);
    }

    /**
     * Archive or unarchive a list
     * 
     * @param string $listId
     * @param bool $archived
     * @return array
     */
    public function archiveList(string $listId, bool $archived = true)
    {
        return $this->updateList($listId, ['closed' => $archived]);
    }

    /**
     * Get cards for a list
     *
     * @param string $listId
     * @return array
     */
    public function getCards(string $listId)
    {
        return $this->request('GET', "/lists/{$listId}/cards");
    }

    /**
     * Create a new card
     *
     * @param string $listId
     * @param string $name
     * @param array $options
     * @return array
     */
    public function createCard(string $listId, string $name, array $options = [])
    {
        $data = array_merge([
            'idList' => $listId,
            'name' => $name,
        ], $options);

        return $this->request('POST', '/cards', $data);
    }

    /**
     * Update a card
     *
     * @param string $cardId
     * @param array $data
     * @return array
     */
    public function updateCard(string $cardId, array $data)
    {
        return $this->request('PUT', "/cards/{$cardId}", $data);
    }

    /**
     * Move a card to a different list
     *
     * @param string $cardId
     * @param string $listId
     * @return array
     */
    public function moveCard(string $cardId, string $listId)
    {
        return $this->updateCard($cardId, ['idList' => $listId]);
    }

    /**
     * Add a comment to a card
     *
     * @param string $cardId
     * @param string $text
     * @return array
     */
    public function addComment(string $cardId, string $text)
    {
        return $this->request('POST', "/cards/{$cardId}/actions/comments", [
            'text' => $text
        ]);
    }

    /**
     * Add a label to a card
     *
     * @param string $cardId
     * @param string $labelId
     * @return array
     */
    public function addLabel(string $cardId, string $labelId)
    {
        return $this->request('POST', "/cards/{$cardId}/idLabels", [
            'value' => $labelId
        ]);
    }

    /**
     * Remove a label from a card
     *
     * @param string $cardId
     * @param string $labelId
     * @return array
     */
    public function removeLabel(string $cardId, string $labelId)
    {
        return $this->request('DELETE', "/cards/{$cardId}/idLabels/{$labelId}");
    }

    /**
     * Get all board members
     *
     * @param string $boardId
     * @return array
     */
    public function getBoardMembers(string $boardId)
    {
        return $this->request('GET', "/boards/{$boardId}/members");
    }

    /**
     * Get all organization members
     *
     * @param string $orgId
     * @return array
     */
    public function getOrganizationMembers(string $orgId)
    {
        return $this->request('GET', "/organizations/{$orgId}/members");
    }

    /**
     * Assign a member to a card
     *
     * @param string $cardId
     * @param string $memberId
     * @return array
     */
    public function assignMemberToCard(string $cardId, string $memberId)
    {
        return $this->request('POST', "/cards/{$cardId}/idMembers", [
            'value' => $memberId
        ]);
    }

    /**
     * Remove a member from a card
     *
     * @param string $cardId
     * @param string $memberId
     * @return array
     */
    public function removeMemberFromCard(string $cardId, string $memberId)
    {
        return $this->request('DELETE', "/cards/{$cardId}/idMembers/{$memberId}");
    }

    /**
     * Get members assigned to a card
     *
     * @param string $cardId
     * @return array
     */
    public function getCardMembers(string $cardId)
    {
        return $this->request('GET', "/cards/{$cardId}/members");
    }

    /**
     * Create a new webhook
     *
     * @param string $callbackUrl
     * @param string $idModel
     * @param string $description
     * @return array
     */
    public function createWebhook(string $callbackUrl, string $idModel, string $description = '')
    {
        return $this->request('POST', '/webhooks', [
            'callbackURL' => $callbackUrl,
            'idModel' => $idModel,
            'description' => $description
        ]);
    }

    /**
     * Delete a webhook
     *
     * @param string $webhookId
     * @return array
     */
    public function deleteWebhook(string $webhookId)
    {
        return $this->request('DELETE', "/webhooks/{$webhookId}");
    }

    /**
     * Get all webhooks
     *
     * @return array
     */
    public function getWebhooks()
    {
        return $this->request('GET', "/tokens/{$this->token}/webhooks");
    }

    /**
     * Add a due date to a card
     *
     * @param string $cardId
     * @param string $dueDate Format: 2023-12-31T12:00:00Z
     * @return array
     */
    public function addDueDate(string $cardId, string $dueDate)
    {
        return $this->updateCard($cardId, ['due' => $dueDate]);
    }

    /**
     * Add a checklist to a card
     *
     * @param string $cardId
     * @param string $name
     * @return array
     */
    public function addChecklist(string $cardId, string $name)
    {
        return $this->request('POST', "/cards/{$cardId}/checklists", [
            'name' => $name
        ]);
    }

    /**
     * Add a checklist item
     *
     * @param string $checklistId
     * @param string $name
     * @return array
     */
    public function addChecklistItem(string $checklistId, string $name)
    {
        return $this->request('POST', "/checklists/{$checklistId}/checkItems", [
            'name' => $name
        ]);
    }

    /**
     * Make a request to the Trello API
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws TrelloException
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            // Always add auth parameters
            $params = [
                'key' => $this->apiKey,
                'token' => $this->token,
            ];
            
            $response = Http::withQueryParameters($params)
                ->asJson();
            
            if ($method === 'GET') {
                $response = $response->get($url, $data);
            } elseif ($method === 'POST') {
                $response = $response->post($url, $data);
            } elseif ($method === 'PUT') {
                $response = $response->put($url, $data);
            } elseif ($method === 'DELETE') {
                $response = $response->delete($url, $data);
            }
            
            if ($response->failed()) {
                throw new TrelloException('Trello API error: ' . $response->body(), $response->status());
            }
            
            return $response->json();
        } catch (\Exception $e) {
            if ($e instanceof TrelloException) {
                throw $e;
            }
            
            Log::error('Trello API error: ' . $e->getMessage());
            throw new TrelloException('Trello API error: ' . $e->getMessage(), 500, $e);
        }
    }
}
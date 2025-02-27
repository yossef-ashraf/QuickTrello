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
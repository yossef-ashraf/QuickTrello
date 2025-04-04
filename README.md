# QuickTrello

A Laravel package for quick and easy integration with the Trello API, providing a comprehensive set of features for managing boards, lists, cards, members, and more.

## Installation

You can install the package via composer:

```bash
composer require quickhelper/quicktrello
```

## Publishing the config file

```bash
php artisan vendor:publish --tag="quicktrello-config"
```

This will publish the config file to `config/quicktrello.php`.

## Configuration

Add the following to your `.env` file:

```
TRELLO_API_KEY=your_trello_api_key
TRELLO_TOKEN=your_trello_token
TRELLO_WEBHOOK_SECRET=optional_webhook_secret
```

You can get your API key and token from [https://trello.com/app-key](https://trello.com/app-key).

## Usage

### Boards

```php
use QuickTrello\Facades\QuickTrello;

// Get all boards
$boards = QuickTrello::getBoards();

// Get a specific board
$board = QuickTrello::getBoard('board_id');

// Get board members
$members = QuickTrello::getBoardMembers('board_id');
```

### Lists

```php
// Get lists for a board
$lists = QuickTrello::getLists('board_id');

// Create a new list
$list = QuickTrello::createList('board_id', 'List Name', [
    'pos' => 'top' // Optional position ('top', 'bottom', or a position value)
]);

// Update a list
$list = QuickTrello::updateList('list_id', [
    'name' => 'New List Name',
    'pos' => 'bottom'
]);

// Archive a list
$list = QuickTrello::archiveList('list_id', true); // Archive
$list = QuickTrello::archiveList('list_id', false); // Unarchive
```

### Cards

```php
// Get cards for a list
$cards = QuickTrello::getCards('list_id');

// Create a card
$card = QuickTrello::createCard('list_id', 'Card title', [
    'desc' => 'Card description',
    'due' => '2025-03-01',
    'pos' => 'top'
]);

// Update a card
$card = QuickTrello::updateCard('card_id', [
    'name' => 'Updated Card Title',
    'desc' => 'Updated description'
]);

// Move a card to another list
$card = QuickTrello::moveCard('card_id', 'new_list_id');

// Add a comment to a card
$comment = QuickTrello::addComment('card_id', 'This is a comment');

// Add a label to a card
$label = QuickTrello::addLabel('card_id', 'label_id');

// Remove a label from a card
$result = QuickTrello::removeLabel('card_id', 'label_id');

// Add a due date to a card
$card = QuickTrello::addDueDate('card_id', '2025-03-01T12:00:00Z');
```

### Members and Assignments

```php
// Get all board members
$members = QuickTrello::getBoardMembers('board_id');

// Get all organization members
$members = QuickTrello::getOrganizationMembers('org_id');

// Assign a member to a card
$result = QuickTrello::assignMemberToCard('card_id', 'member_id');

// Remove a member from a card
$result = QuickTrello::removeMemberFromCard('card_id', 'member_id');

// Get members assigned to a card
$members = QuickTrello::getCardMembers('card_id');
```

### Checklists

```php
// Add a checklist to a card
$checklist = QuickTrello::addChecklist('card_id', 'Checklist Name');

// Add an item to a checklist
$item = QuickTrello::addChecklistItem('checklist_id', 'Checklist Item');
```

### Webhooks

The package automatically registers a webhook endpoint at `/api/trello/webhooks` (configurable). You can create webhooks in Trello to receive notifications when events occur:

```php
// Create a webhook
$webhook = QuickTrello::createWebhook(
    'https://your-app.com/api/trello/webhooks',
    'model_id', // board_id, card_id, etc.
    'Description of webhook'
);

// Get all webhooks
$webhooks = QuickTrello::getWebhooks();

// Delete a webhook
$result = QuickTrello::deleteWebhook('webhook_id');
```

### Listening for Webhook Events

You can listen for webhook events in your Laravel application:

```php
// In your EventServiceProvider
Event::listen('quicktrello.webhook_received', function ($payload) {
    // Handle all webhook events
});

// Listen for specific event types
Event::listen('quicktrello.createCard', function ($payload) {
    // Handle card creation events
});

Event::listen('quicktrello.updateCard', function ($payload) {
    // Handle card update events
});

Event::listen('quicktrello.addMemberToCard', function ($payload) {
    // Handle when a member is assigned to a card
});

Event::listen('quicktrello.createList', function ($payload) {
    // Handle list creation events
});
```

## Advanced Usage

### Error Handling

The package throws `TrelloException` when API errors occur. You can catch these exceptions to handle errors gracefully:

```php
use QuickTrello\Exceptions\TrelloException;

try {
    $card = QuickTrello::createCard('list_id', 'Card title');
} catch (TrelloException $e) {
    // Handle error
    $statusCode = $e->getCode();
    $message = $e->getMessage();
}
```

### Webhook Verification

For added security, you can set a webhook secret in your config and the package will verify incoming webhook requests:

```php
// In config/quicktrello.php
'webhook_secret' => env('TRELLO_WEBHOOK_SECRET'),
```

## Available Methods

### Boards
- `getBoards()`
- `getBoard(string $boardId)`
- `getBoardMembers(string $boardId)`

### Lists
- `getLists(string $boardId)`
- `createList(string $boardId, string $name, array $options = [])`
- `updateList(string $listId, array $data)`
- `archiveList(string $listId, bool $archived = true)`

### Cards
- `getCards(string $listId)`
- `createCard(string $listId, string $name, array $options = [])`
- `updateCard(string $cardId, array $data)`
- `moveCard(string $cardId, string $listId)`
- `addComment(string $cardId, string $text)`
- `addLabel(string $cardId, string $labelId)`
- `removeLabel(string $cardId, string $labelId)`
- `addDueDate(string $cardId, string $dueDate)`

### Members
- `getBoardMembers(string $boardId)`
- `getOrganizationMembers(string $orgId)`
- `assignMemberToCard(string $cardId, string $memberId)`
- `removeMemberFromCard(string $cardId, string $memberId)`
- `getCardMembers(string $cardId)`

### Checklists
- `addChecklist(string $cardId, string $name)`
- `addChecklistItem(string $checklistId, string $name)`

### Webhooks
- `createWebhook(string $callbackUrl, string $idModel, string $description = '')`
- `deleteWebhook(string $webhookId)`
- `getWebhooks()`

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Best regards,  
[Yossef Ashraf](https://github.com/yossef-ashraf)
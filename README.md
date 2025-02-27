# QuickTrello

A Laravel package for quick and easy integration with the Trello API.

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

### Basic Usage

```php
use QuickTrello\Facades\QuickTrello;

// Get all boards
$boards = QuickTrello::getBoards();

// Get lists for a board
$lists = QuickTrello::getLists('board_id');

// Get cards for a list
$cards = QuickTrello::getCards('list_id');

// Create a card
$card = QuickTrello::createCard('list_id', 'Card title', [
    'desc' => 'Card description',
    'due' => '2025-03-01'
]);

// Move a card to another list
$card = QuickTrello::moveCard('card_id', 'new_list_id');

// Add a comment to a card
$comment = QuickTrello::addComment('card_id', 'This is a comment');
```

### Webhooks

The package automatically registers a webhook endpoint at `/api/trello/webhooks` (configurable). You can create a webhook in Trello to receive notifications when events occur:

```php
// Create a webhook
$webhook = QuickTrello::createWebhook(
    'https://your-app.com/api/trello/webhooks',
    'model_id', // board_id, card_id, etc.
    'Description of webhook'
);
```

### Listening for Webhook Events

You can listen for webhook events in your Laravel application:

```php
// In your EventServiceProvider
Event::listen('quicktrello.webhook_received', function ($payload) {
    // Handle all webhook events
});

Event::listen('quicktrello.createCard', function ($payload) {
    // Handle card creation events
});

Event::listen('quicktrello.updateCard', function ($payload) {
    // Handle card update events
});
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
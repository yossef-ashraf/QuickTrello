<?php

namespace QuickTrello\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class TrelloWebhookController extends Controller
{
    /**
     * Handle incoming webhook requests from Trello
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        // Log the webhook if debugging is enabled
        if (config('app.debug')) {
            Log::debug('Trello webhook received', $payload);
        }
        
        // Get the action type
        $action = $payload['action'] ?? null;
        $actionType = $action['type'] ?? '';
        
        // Dispatch an event for this action type
        $eventName = 'quicktrello.' . str_replace('.', '_', $actionType);
        Event::dispatch($eventName, [$payload]);
        
        // Also dispatch a generic webhook event
        Event::dispatch('quicktrello.webhook_received', [$payload]);
        
        return response()->json(['success' => true]);
    }
}
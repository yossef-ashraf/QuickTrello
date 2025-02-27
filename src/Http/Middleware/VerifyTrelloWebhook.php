<?php

namespace QuickTrello\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTrelloWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If no webhook secret is configured, skip verification
        $secret = config('quicktrello.webhook_secret');
        if (empty($secret)) {
            return $next($request);
        }
        
        // Trello doesn't send a signature, so we can only do basic verification
        // This is a placeholder for future improvements if Trello adds signature verification
        
        // For now, you could check the source IP address if needed
        // $allowedIps = ['...', '...']; // Trello IP ranges
        // if (!in_array($request->ip(), $allowedIps)) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        
        return $next($request);
    }
}
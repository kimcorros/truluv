<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /**
     * Post a new message to a conversation.
     */
    public function store(StoreMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->authorize('sendMessage', $conversation);

        $message = new Message($request->validated());
        $message->sender_id = $request->user()->id;

        $conversation->messages()->save($message);

        return to_route('conversations.show', $conversation);
    }
}

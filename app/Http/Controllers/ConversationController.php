<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartConversationRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ConversationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConversationController extends Controller
{
    /**
     * Inbox: the current user's conversations with the latest message preview.
     */
    public function index(Request $request): Response
    {
        $me = $request->user();

        $conversations = Conversation::query()
            ->forUser($me)
            ->with(['userOne.profile', 'userTwo.profile', 'latestMessage'])
            ->withMax('messages as last_message_at', 'created_at')
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function (Conversation $conversation) use ($me) {
                $other = $conversation->otherParticipant($me);

                return [
                    'id' => $conversation->id,
                    'other_name' => $other->name,
                    'other_photo' => $other->profile?->photo_url,
                    'last_message' => $conversation->latestMessage?->body,
                ];
            });

        return Inertia::render('conversations/Index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Start (or reopen) a conversation with another user.
     */
    public function store(StartConversationRequest $request, ConversationService $service): RedirectResponse
    {
        $recipient = User::findOrFail($request->validated('recipient_id'));

        $conversation = $service->findOrCreateBetween($request->user(), $recipient);

        return to_route('conversations.show', $conversation);
    }

    /**
     * Show a single conversation thread.
     */
    public function show(Request $request, Conversation $conversation): Response
    {
        $this->authorize('view', $conversation);

        $me = $request->user();
        $conversation->load(['messages.sender', 'userOne.profile', 'userTwo.profile']);
        $other = $conversation->otherParticipant($me);

        return Inertia::render('conversations/Show', [
            'conversation' => [
                'id' => $conversation->id,
                'other_name' => $other->name,
                'other_photo' => $other->profile?->photo_url,
            ],
            'messages' => $conversation->messages->map(fn (Message $message) => [
                'id' => $message->id,
                'body' => $message->body,
                'mine' => $message->sender_id === $me->id,
                'sender_name' => $message->sender->name,
                'sent_at' => $message->created_at->format('M j, Y g:i A'),
            ]),
        ]);
    }
}

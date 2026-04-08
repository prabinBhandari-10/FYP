<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Claim;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $user = $this->currentAuthenticatedUser();

        $conversations = ChatConversation::query()
            ->with([
                'claim.report',
                'finder:id,name',
                'claimant:id,name',
                'latestMessage.sender:id,name',
            ])
            ->withCount([
                'messages as unread_count' => function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)
                        ->whereNull('read_at');
                },
            ])
            ->where(function ($query) use ($user) {
                $query->where('finder_id', $user->id)
                    ->orWhere('claimant_id', $user->id);
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->paginate(12);

        return view('chat.index', [
            'conversations' => $conversations,
            'currentUser' => $user,
        ]);
    }

    public function show(Request $request, Claim $claim): View
    {
        $user = $this->currentUser($claim);
        $conversation = $this->conversationForClaim($claim);

        $unreadCount = $conversation->messages()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $messages = $conversation->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();

        $conversation->messages()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('chat.show', [
            'claim' => $claim->load(['report.user', 'user']),
            'conversation' => $conversation->load(['finder', 'claimant']),
            'messages' => $messages,
            'currentUser' => $user,
            'otherUser' => $this->otherUser($claim, $user->id),
            'unreadCount' => $unreadCount,
        ]);
    }

    public function store(Request $request, Claim $claim): RedirectResponse
    {
        $user = $this->currentUser($claim);
        $conversation = $this->conversationForClaim($claim);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $otherUser = $this->otherUser($claim, $user->id);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'claim_id' => $claim->id,
            'sender_id' => $user->id,
            'receiver_id' => $otherUser->id,
            'message' => $validated['message'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()
            ->route('chat.show', $claim)
            ->with('success', 'Message sent.');
    }

    protected function conversationForClaim(Claim $claim): ChatConversation
    {
        $report = $claim->report()->select('id', 'user_id', 'title')->firstOrFail();

        return ChatConversation::firstOrCreate(
            ['claim_id' => $claim->id],
            [
                'finder_id' => $report->user_id,
                'claimant_id' => $claim->user_id,
                'approved_at' => now(),
            ]
        );
    }

    protected function otherUser(Claim $claim, int $currentUserId)
    {
        $claim->loadMissing(['report.user', 'user']);

        if ($claim->user_id === $currentUserId) {
            return $claim->report->user;
        }

        return $claim->user;
    }

    protected function currentUser(Claim $claim): Authenticatable
    {
        $report = $claim->report()->select('id', 'user_id')->firstOrFail();
        $allowedIds = [$claim->user_id, $report->user_id];

        $webUser = Auth::guard('web')->user();
        if ($webUser && in_array($webUser->id, $allowedIds, true)) {
            Auth::shouldUse('web');

            return $webUser;
        }

        $adminUser = Auth::guard('admin')->user();
        if ($adminUser && in_array($adminUser->id, $allowedIds, true)) {
            Auth::shouldUse('admin');

            return $adminUser;
        }

        abort(403);
    }

    protected function currentAuthenticatedUser(): Authenticatable
    {
        $activeUser = Auth::user();
        if ($activeUser) {
            return $activeUser;
        }

        $webUser = Auth::guard('web')->user();
        if ($webUser) {
            Auth::shouldUse('web');

            return $webUser;
        }

        $adminUser = Auth::guard('admin')->user();
        if ($adminUser) {
            Auth::shouldUse('admin');

            return $adminUser;
        }

        abort(403);
    }
}
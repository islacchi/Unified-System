<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class ChatBox extends Component
{
    public bool $open = false;
    public ?int $receiverId = null;
    public string $body = '';

    public function getListeners(): array
    {
        return [
            'echo:chat,.message-sent' => '$refresh',
        ];
    }

    public function markAsRead()
    {
        Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function setReceiver(?int $userId)
    {
        $this->receiverId = $userId;
    }

    public function sendMessage()
    {
        $this->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->receiverId,
            'body'        => $this->body,
        ]);

        $this->body = '';
        $this->dispatch('$refresh');
        $this->dispatch('chat-sent');
    }

    public function getMessagesProperty()
    {
        $query = Message::query();

        if ($this->receiverId === null) {
            // Group chat — only group messages
            $query->whereNull('receiver_id');
        } else {
            // Private chat — messages between the two users
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('sender_id', auth()->id())
                        ->where('receiver_id', $this->receiverId);
                })->orWhere(function ($sub) {
                    $sub->where('sender_id', $this->receiverId)
                        ->where('receiver_id', auth()->id());
                });
            });
        }

        return $query->with('sender')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();
    }

    public function getUnreadCountProperty(): int
    {
        return Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    public function getUnreadPerUserProperty(): array
    {
        $counts = Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id')
            ->toArray();

        return $counts;
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.chat-box', [
            'messages'       => $this->messages,
            'unreadCount'    => $this->unreadCount,
            'unreadPerUser'  => $this->unreadPerUser,
            'users'          => $this->users,
        ]);
    }
}
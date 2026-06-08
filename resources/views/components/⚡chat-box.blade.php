<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class ChatBox extends Component
{
    public string $body = '';
    public ?int $receiverId = null; // null = All
    public bool $open = false;

    protected $listeners = ['refreshChat' => '$refresh'];

    public function sendMessage(): void
    {
        $this->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->receiverId,
            'body'        => $this->body,
        ]);

        $this->body = '';
    }

    public function setReceiver(?int $userId): void
    {
        $this->receiverId = $userId;
        $this->markAsRead();
    }

    public function markAsRead(): void
    {
        $query = Message::whereNull('read_at')
            ->where('sender_id', '!=', auth()->id());

        if ($this->receiverId === null) {
            $query->whereNull('receiver_id');
        } else {
            $query->where(function ($q) {
                $q->where('sender_id', $this->receiverId)
                  ->where('receiver_id', auth()->id());
            });
        }

        $query->update(['read_at' => now()]);
    }

    public function getMessagesProperty()
    {
        $query = Message::with('sender')->latest()->limit(50);

        if ($this->receiverId === null) {
            $query->whereNull('receiver_id');
        } else {
            $query->where(function ($q) {
                $q->where('sender_id', auth()->id())
                  ->where('receiver_id', $this->receiverId);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->receiverId)
                  ->where('receiver_id', auth()->id());
            });
        }

        return $query->get()->reverse()->values();
    }

    public function getUnreadCountProperty(): int
    {
        return Message::whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->where(function ($q) {
                $q->whereNull('receiver_id')
                  ->orWhere('receiver_id', auth()->id());
            })
            ->count();
    }

    public function getUnreadPerUserProperty(): array
    {
        return Message::whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->where('receiver_id', auth()->id())
            ->selectRaw('sender_id, count(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.chat-box', [
            'messages'      => $this->messages,
            'users'         => User::where('id', '!=', auth()->id())->orderBy('name')->get(),
            'unreadCount'   => $this->unreadCount,
            'unreadPerUser' => $this->unreadPerUser,
        ]);
    }
}
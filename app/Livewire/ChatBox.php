<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ChatBox extends Component
{
    use WithFileUploads;

    public bool $open = false;
    public ?int $receiverId = null;
    public string $body = '';
    public $file = null;
    public string $search = '';

    protected $rules = [
        'body' => 'nullable|string|max:1000',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt|max:10240',
    ];

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
        $this->search = '';
    }

    public function sendMessage()
    {
        $this->validate();

        if (empty($this->body) && !$this->file) {
            return;
        }

        $filePath = null;
        $fileType = null;
        $fileName = null;

        if ($this->file) {
            $filePath = $this->file->store('chat', 'public');
            $fileType = $this->file->getMimeType();
            $fileName = $this->file->getClientOriginalName();
        }

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->receiverId,
            'body'        => $this->body ?? '',
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
        ]);

        $this->body = '';
        $this->file = null;
        $this->dispatch('$refresh');
        $this->dispatch('chat-sent');
    }

    public function deleteMessage(int $messageId)
    {
        $message = Message::find($messageId);
        if (!$message || !$message->canDelete()) return;

        // Use deleted_by so only this user can't see it, the other person still can
        $message->deleted_by = auth()->id();
        $message->save();
    }

    public function unsendMessage(int $messageId)
    {
        $message = Message::find($messageId);
        if (!$message || !$message->canUnsend()) return;

        // Use deleted_at so neither user can see it
        $message->deleted_at = now();
        $message->save();
    }

    public function deleteConversation()
    {
        if ($this->receiverId === null) return;

        $userId = auth()->id();
        $otherId = $this->receiverId;

        // Mark ALL messages in this conversation as deleted by me (only I can't see them)
        Message::where(function ($q) use ($userId, $otherId) {
            $q->where(function ($sub) use ($userId, $otherId) {
                $sub->where('sender_id', $userId)->where('receiver_id', $otherId);
            })->orWhere(function ($sub) use ($userId, $otherId) {
                $sub->where('sender_id', $otherId)->where('receiver_id', $userId);
            });
        })->update(['deleted_by' => $userId]);

        $this->receiverId = null;
        $this->dispatch('$refresh');
    }

    public function getMessagesProperty()
    {
        $query = Message::query()->notDeletedForMe();

        if ($this->receiverId === null) {
            $query->whereNull('receiver_id');
        } else {
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

        if ($this->search) {
            $query->where('body', 'LIKE', '%' . $this->search . '%');
        }

        return $query->with('sender')
            ->whereNotNull('body')
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
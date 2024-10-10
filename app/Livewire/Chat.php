<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Chat extends Component
{
    use WithPagination;

    public User $receiver;
    public string $currentMessageText;
    public Collection $messages;

    public function mount(User $receiver): void
    {
        $this->receiver = $receiver;
        $this->messages = collect();
        $this->loadInitialMessages();
    }

    public function send(): void
    {
        // Create and store the new message
        $message = ChatMessage::create([
            'message' => $this->currentMessageText,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiver->id,
        ]);

        // Broadcast the message to the receiver
        broadcast(new MessageSent($message));

        // Append the new message to the messages collection
        $this->messages->push($message);

    }

    public function loadInitialMessages(): void
    {
        // Load the initial chat messages between the authenticated user and the receiver
        $this->messages = ChatMessage::query()
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $this->receiver->id);
            })
            ->orWhere(function ($query) {
                $query->where('sender_id', $this->receiver->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with('sender', 'receiver')
            ->orderBy('created_at')
            ->get();
    }

    public function appendMessage($message): void
    {
        // Append the new message to the existing messages collection
        $this->messages->push(ChatMessage::find($message['id']));
    }

    public function render()
    {
        // Return the chat view with the updated messages collection
        return view('livewire.chat', [
            'messages' => $this->messages
        ]);
    }
}

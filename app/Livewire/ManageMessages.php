<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;

class ManageMessages extends Component
{
    public $subject = '';
    public $message = '';
    public $receiverType = 'parent'; // parent or teacher
    public $receiverId;
    public $messages = [];
    public $unreadCount = 0;

    public $editMessageId;
    public $editSubject = '';
    public $editMessage = '';
    public $editRead = false;

    public function render()
    {
        // Get messages where current user is either sender or receiver
        // For demo purposes, we'll fetch recent messages
        $this->messages = Message::with(['sender', 'receiver'])
            ->latest()
            ->take(20)
            ->get();
        
        $this->unreadCount = Message::where('receiver_id', auth()->id())
            ->where('read_by_receiver', false)
            ->count();

        return view('livewire.manage-messages', [
            'messages' => $this->messages,
            'unreadCount' => $this->unreadCount,
            'users' => User::where('role', 'parent')->get(),
        ]);
    }

    public function sendMessage()
    {
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverId,
            'subject' => $this->subject,
            'message' => $this->message,
            'read_by_receiver' => false,
        ]);

        $this->reset(['subject', 'message', 'receiverId']);
        $this->emit('alert', 'Message sent successfully!');
    }

    public function markAsRead($id)
    {
        $message = Message::find($id);
        $message->update(['read_by_receiver' => true]);
        $this->emit('alert', 'Message marked as read!');
    }
}
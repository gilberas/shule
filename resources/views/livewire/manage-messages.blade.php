<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <div class="border-b border-border pb-4 mb-6 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-foreground">Messages</h4>
            <span class="inline-flex items-center rounded-full bg-red-500/10 px-2 py-1 text-xs font-medium text-red-600">{{ $unreadCount }}</span>
        </div>

        <!-- Message Form -->
        <div class="mb-6">
            <h5 class="text-base font-medium text-foreground mb-4">Send New Message</h5>
            <form wire:submit="sendMessage">
                <input type="hidden" name="receiverType" value="{{ $receiverType }}">
                <input type="hidden" name="receiverId" value="{{ $receiverId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">To</label>
                        <select wire:model.debounce.300ms="receiverId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Recipient</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Subject</label>
                        <input type="text" wire:model.debounce.300ms="subject" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-foreground mb-1">Message</label>
                    <textarea wire:model.debounce.300ms="message" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3" required></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Send Message</button>
                </div>
            </form>
        </div>

        <!-- Messages List -->
        <h5 class="text-base font-medium text-foreground mb-4">Message History</h5>
        @if($messages->isEmpty())
        <p class="text-sm text-muted-foreground">No messages yet.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Subject</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">From</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">To</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Read</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($messages as $message)
                    <tr>
                        <td class="px-4 py-3">{{ $message->subject }}</td>
                        <td class="px-4 py-3">{{ $message->sender->name }}</td>
                        <td class="px-4 py-3">{{ $message->receiver->name }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($message->created_at)->format('M d, H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($message->read_by_receiver)
                            <span class="inline-flex items-center rounded-full bg-green-500/10 px-2 py-1 text-xs font-medium text-green-600">Read</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-red-500/10 px-2 py-1 text-xs font-medium text-red-600">Unread</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(auth()->id() == $message->receiver_id)
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="markAsRead({{ $message->id }})">Mark Read</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

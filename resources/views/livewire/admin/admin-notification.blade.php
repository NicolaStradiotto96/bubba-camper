<div class="relative inline-flex" wire:poll.60s>
    @if ($hasPending)
        <div class="relative mr-3">
            <span class="absolute -top-1.5 -right-1.5 h-3 w-3 bg-amber-500 rounded-full animate-ping"></span>
            <span class="absolute -top-1.5 -right-1.5 h-3 w-3 bg-amber-500 rounded-full"></span>
        </div>
    @endif
</div>

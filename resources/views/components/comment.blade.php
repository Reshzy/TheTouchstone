@props(['comment', 'article', 'level' => 0, 'maxLevel' => 3])

<div id="comment-{{ $comment->id }}" class="border-l-4 {{ $level === 0 ? 'border-gray-300' : 'border-gray-200' }} pl-4 py-3 @if($level > 0) mt-3 @endif">
    <div class="flex justify-between items-start">
        <div class="flex items-start mb-2">
            <!-- User avatar -->
            <div class="flex-shrink-0 mr-3">
                <div class="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($comment->author->name, 0, 2)) }}</span>
                </div>
            </div>
            <!-- Comment content -->
            <div>
                <div class="font-medium text-gray-800">{{ $comment->author->name }}</div>
                <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                <div class="mt-1 text-gray-700">{{ $comment->content }}</div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center text-xs space-x-2 text-gray-500">
            @auth
                @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
                    <a href="{{ route('comments.edit', $comment) }}" class="hover:text-blue-600">Edit</a>
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline m-auto" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="hover:text-red-600">Delete</button>
                    </form>
                @endif
                
                @if($level < $maxLevel)
                    <button class="reply-toggle hover:text-blue-600" data-comment-id="{{ $comment->id }}">Reply</button>
                @endif
            @endauth
        </div>
    </div>
    
    <!-- Reply form (hidden by default) -->
    @auth
        @if($level < $maxLevel)
            <div id="reply-form-{{ $comment->id }}" class="reply-form hidden mt-3">
                <form action="{{ route('comments.reply', $comment) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea name="content" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a reply..."></textarea>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="cancel-reply px-3 py-1 text-xs text-gray-600 hover:text-gray-800" data-comment-id="{{ $comment->id }}">Cancel</button>
                        <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">Post Reply</button>
                    </div>
                </form>
            </div>
        @endif
    @endauth
    
    <!-- Comment replies -->
    @if($comment->replies->count() > 0)
        <div class="ml-2 mt-2">
            @foreach($comment->replies as $reply)
                <x-comment :comment="$reply" :article="$article" :level="$level + 1" :max-level="$maxLevel" />
            @endforeach
        </div>
    @endif
</div> 
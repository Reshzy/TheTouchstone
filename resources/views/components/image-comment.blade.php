@props(['comment', 'level' => 0, 'maxLevel' => 3])

<div id="image-comment-{{ $comment->id }}" class="border-l-4 {{ $level === 0 ? 'border-gray-300' : 'border-gray-200' }} pl-3 py-2 @if($level > 0) mt-2 @endif">
    <div class="flex justify-between items-start">
        <div class="flex items-start mb-1">
            <!-- User avatar -->
            <div class="flex-shrink-0 mr-2">
                <div class="h-7 w-7 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($comment->author->name, 0, 2)) }}</span>
                </div>
            </div>
            <!-- Comment content -->
            <div>
                <div class="font-medium text-sm text-gray-800">{{ $comment->author->name }}</div>
                <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                <div class="mt-1 text-sm text-gray-700" id="image-comment-content-{{ $comment->id }}">{{ $comment->content }}</div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex text-xs space-x-2 text-gray-500">
            @auth
                @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
                    <button class="edit-image-comment hover:text-blue-600" data-comment-id="{{ $comment->id }}">Edit</button>
                    <button class="delete-image-comment hover:text-red-600" data-comment-id="{{ $comment->id }}">Delete</button>
                @endif
                
                @if($level < $maxLevel)
                    <button class="reply-image-comment hover:text-blue-600" data-comment-id="{{ $comment->id }}">Reply</button>
                @endif
            @endauth
        </div>
    </div>
    
    <!-- Edit form (hidden by default) -->
    @auth
        @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
            <div id="edit-image-comment-form-{{ $comment->id }}" class="edit-image-comment-form hidden mt-2">
                <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ $comment->content }}</textarea>
                <div class="flex justify-end space-x-2 mt-1">
                    <button type="button" class="cancel-edit-image-comment px-3 py-1 text-xs text-gray-600 hover:text-gray-800" data-comment-id="{{ $comment->id }}">Cancel</button>
                    <button type="button" class="save-image-comment-edit px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700" data-comment-id="{{ $comment->id }}">Save</button>
                </div>
            </div>
        @endif
    @endauth
    
    <!-- Reply form (hidden by default) -->
    @auth
        @if($level < $maxLevel)
            <div id="reply-image-comment-form-{{ $comment->id }}" class="reply-image-comment-form hidden mt-2">
                <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Write a reply..."></textarea>
                <div class="flex justify-end space-x-2 mt-1">
                    <button type="button" class="cancel-reply-image-comment px-3 py-1 text-xs text-gray-600 hover:text-gray-800" data-comment-id="{{ $comment->id }}">Cancel</button>
                    <button type="button" class="post-image-comment-reply px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700" data-comment-id="{{ $comment->id }}" data-parent-id="{{ $comment->id }}">Reply</button>
                </div>
            </div>
        @endif
    @endauth
    
    <!-- Comment replies -->
    @if(isset($comment->replies) && $comment->replies->count() > 0)
        <div class="ml-2 mt-2 replies-container-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                <x-image-comment :comment="$reply" :level="$level + 1" :max-level="$maxLevel" />
            @endforeach
        </div>
    @else
        <div class="replies-container-{{ $comment->id }}"></div>
    @endif
</div> 
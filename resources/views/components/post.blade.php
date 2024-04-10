<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <img class="avatar-tiny" src="{{$post->user->avatar}}" />
        <strong>{{$post->title}}</strong> 
        <span class="text-muted"> 
            @if (!isset($hideAuthor))
                by {{$post->user->username}}
            @endif
            on {{$post->created_at->format('j/n/Y')}}
        </span>
    </div>
    <span style="margin-right: 1.5rem">
        <i class="fa fa-heart" aria-hidden="true"></i> {{ $post->likes()->count() }}
        &nbsp;&nbsp;
        <i class="fa fa-comment" aria-hidden="true"></i> {{ $post->comments()->count() }}
    </span>    
</a>

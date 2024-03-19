<x-layout :doctitle="($post->title)">

    <style>
        .submit-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.4rem;
            color: #007bff; /* Change this to your desired color */
            }

        .submit-btn:focus {
            outline: none;
        }

        .chat-box {
            padding-bottom: 10px;
        }

        .chat-text {
            background-color: #c9cbcc;
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
        }
        
        .chat-avatar {
        flex-shrink: 0;
        }

        .chat-content {
        flex-grow: 1;
        }

        .chat-header {
        font-size: 0.9rem;
        }

        .chat-timestamp {
        font-size: 0.8rem;
        }   

        .like-btn {
			display: inline-flex;
			align-items: center;
			margin-top: 0.2rem;
			padding: 0rem 0.5rem;
			border-radius: 0.25rem;
			border-color: rgb(249, 214, 214);
			background-color: #e2e8f03f;
			color: #4a5568;
			font-size: 0.9rem;
			font-weight: 500;
			transition: background-color 0.2s ease-in-out;
		}

		.like-btn:hover {
			background-color: #cbd5e0;
		}

		.like-btn i {
			margin-right: 0.5rem;
		}

		.like-btn .liked {
			color: #e53e3e;
		}

    </style>

    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
            <h2>{{$post->title}}</h2>

            @can('update',$post) {{-- Only the author can edit and delete, because we made a PostPolicy --}}      
            <span class="pt-2">
                <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
            </span>
            @endcan

        </div>
        
        <p class="text-muted small mb-4">
            <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
            Posted by <a href="/profile/{{$post->user->username}}">{{$post->user->username}}</a> on {{$post->created_at->format('j/n/Y')}}
        </p>
        
        <div class="body-content">
            {!! $post->body !!} {{-- disable the checking in order to use the markdown function--}}
        </div>
        <hr>

        <form action="/like" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <button type="submit" class="like-btn">
                @if ($post->isLikedByUser(Auth::id()))
                    <i class="fas fa-heart liked"></i>&nbsp Dislike
                @else
                    <i class="far fa-heart"></i>&nbsp Like
                @endif
            </button>
        </form>

        {{-- -----------------------User comment ---------------------------------- --}}

        <form action="/usercomment" method="post">
            <div class="mt-3 d-flex flex-row align-items-center p-3 form-color">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">     
                <img src="{{auth()->user()->avatar}}" width="35" class="rounded-circle mr-2">
                <input type="text" class="form-control" placeholder="Add a comment..." name = "content">&nbsp &nbsp
                <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i>
                </button>            
            </div>
        </form>
        {{-- ----------------------------------------------------------------------- --}}

        {{-- display the comments of users who have commented on the particular post --}}
        
        <div class="mt-3 align-items-center p-3 form-color">
                @if($comments->isNotEmpty())
                    @foreach($comments as $comment)
                        <div class="chat-box d-flex mb-4">
                            <div class="chat-avatar mr-2">
                                {{-- <img src="/storage/avatars/{{  $comment->avatar  }}" width="35" class="rounded-circle"> --}}
                                @if($comment->avatar)
                                    <img src="/storage/avatars/{{  $comment->avatar  }}" width="35" class="rounded-circle">
                                @else
                                    <img src="{{ asset('storage/avatars/456212.png') }}" width="35" class="rounded-circle">
                                @endif
                            </div>
                            <div class="chat-content">
                                <div class="chat-header d-flex align-items-center mb-1">
                                    <h6 class="chat-username mb-0 mr-2">{{ $comment->username }}</h6>
                                    <small class="chat-timestamp text-muted">on {{ \Carbon\Carbon::parse($comment->created_at)->format('j/n/Y H:i:s') }}</small>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    @if(Auth::check() && $comment->user_id == Auth::user()->id)
                                        <form class="delete-post-form d-inline" action="/comment/{{$comment->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                                <div class="chat-text bg-light p-2 rounded">
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>There are no comments. Write one!</p>
                @endif

        </div>

        {{-- ------------------------------------------------------------------------ --}}
        
    </div>

</x-layout>


<x-layout>
	<style>
		.like-section {
			display: flex;
			align-items: center;
			margin-bottom: 1rem;
		}

		.like-count {
			margin-top: 0.2rem;
			font-size: 0.9rem;
			color: #666;
			margin-right: 0.5rem;
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

	<script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>

{{-- ----------------------------------------------------------------------- --}}

     {{-- <div class="container py-md-5 container--narrow">

      @unless ($posts->isEmpty())
        <h2 class= "text-center mb-4">The latest from those you follow</h2>
        <div class="list-group">
          @foreach ($posts as $post) 
            <div>   
            <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$post->user->avatar}}" />
            <strong>{{$post->title}}</strong> 
            <span class= "text-muted"> 
              @if (!isset($hideAuthor))
              by {{$post->user->username}}
              @endif
              on {{$post->created_at->format('j/n/Y')}}
            </span>
            </a>

			<div class="like-section">
				<span class="like-count">Likes: {{ session('likeCount') ?? $post->likes()->count() }}</span>
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
			</div>

            </div>
          @endforeach
        </div>

        <div class="mt-4">
        {{$posts->links()}}
        </div>

      @else
            <div class="text-center"> 
              <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
              <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
            </div>

      @endunless
      
    </div> --}}
 
    {{-- ----------------------------------------------------------------------- --}}


{{--  New --}}

<div class="container py-md-5 container--narrow">
    <!-- Display posts from those you follow -->
    @unless ($followingPosts->isEmpty())
        <h2 class="text-center mb-4">The latest from those you follow</h2>
        <div class="list-group">
            @foreach ($followingPosts as $post)
                <div>   
                    <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                        <img class="avatar-tiny" src="{{$post->user->avatar}}" />
                        <strong>{{$post->title}}</strong> 
                        <span class="text-muted"> 
                            by {{$post->user->username}}
                            on {{$post->created_at->format('j/n/Y')}}
                        </span>
                    </a>
                    <!-- Like button section -->
                    <div class="like-section">
                        <span class="like-count">Likes: {{ session('likeCount') ?? $post->likes()->count() }}</span>
                        {{-- <form action="/like" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button type="submit" class="like-btn">
                                @if ($post->isLikedByUser(Auth::id()))
                                    <i class="fas fa-heart liked"></i>&nbsp Dislike
                                @else
                                    <i class="far fa-heart"></i>&nbsp Like
                                @endif
                            </button>
                        </form> --}}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{$followingPosts->links()}}
        </div>
    @else
        <div class="text-center"> 
            <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
            <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
        </div>
    @endunless

    <!-- Display posts from users within 300km -->
    @unless ($postsWithin300km->isEmpty())
        <h2 class="text-center mb-4">See what others are upto in your area</h2>
        <div class="list-group">
            @foreach ($postsWithin300km as $post)
                <div>   
                    <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                        <img class="avatar-tiny" src="{{$post->user->avatar}}" />
                        <strong>{{$post->title}}</strong> 
                        <span class="text-muted"> 
                            by {{$post->user->username}}
                            on {{$post->created_at->format('j/n/Y')}}
                        </span>
                    </a>
                    <!-- Like button section -->
                    <div class="like-section">
                        <span class="like-count">Likes: {{ session('likeCount') ?? $post->likes()->count() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endunless

    <!-- Display posts from users, distance > 2000km -->
    @unless ($postsGreater2000km->isEmpty())
        <h2 class="text-center mb-4">Curious about what people are doing in distant?</h2>
        <div class="list-group">
            @foreach ($postsGreater2000km as $post)
                <div>   
                    <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                        <img class="avatar-tiny" src="{{$post->user->avatar}}" />
                        <strong>{{$post->title}}</strong> 
                        <span class="text-muted"> 
                            by {{$post->user->username}}
                            on {{$post->created_at->format('j/n/Y')}}
                        </span>
                    </a>
                    <!-- Like button section -->
                    <div class="like-section">
                        <span class="like-count">Likes: {{ session('likeCount') ?? $post->likes()->count() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endunless

</div>


{{-- End New --}}









    <script>
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');

            fetch('/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: `postId=${postId}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to like the post');
                }
                console.log('Post liked successfully');

                // Update the like count after successful like
                const likeCountElement = this.nextElementSibling;
                if (likeCountElement && likeCountElement.classList.contains('like-count')) {
                    fetch(`/like/count/${postId}`)
                        .then(response => response.text())
                        .then(data => {
                            likeCountElement.textContent = `Likes: ${data}`;
                        })
                        .catch(error => {
                            console.error('Error updating like count:', error);
                        });
                }
            })
            .catch(error => {
                console.error('Error liking the post:', error);
            });
        });
    });
</script>


</x-layout>
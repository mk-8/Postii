<x-layout>

	<script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>


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
                        <span class="like-count">Comments: {{ session('likeCount') ?? $post->comments()->count() }}</span>
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
                        <span class="like-count">Comments: {{ session('likeCount') ?? $post->comments()->count() }}</span>
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
                        <span class="like-count">Comments: {{ session('likeCount') ?? $post->comments()->count() }}</span>
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
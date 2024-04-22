<x-layout>

	<script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>

    <style>
        .category.selected {
                background-color: #007bff;
        }
    </style>


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

    
    <h2 class="text-center mb-4">Browse by category
    </h2>
    <form id="categoryForm" action="/" method="get">
        <div class="category-container">
                <span class="category" data-category="cars">Cars</span>
                <span class="category" data-category="movies">Movies</span>
                <span class="category" data-category="music">Music</span>
                <span class="category" data-category="hike">Hike</span>
                <span class="category" data-category="cafe">Cafe</span>
                <span class="category" data-category="country">Country</span>
                <span class="category" data-category="accessories">Accessories</span>
                <span class="category" data-category="clothing">Clothing</span>
                <span class="category" data-category="dance">Dance</span>
                <span class="category" data-category="food">Food</span>
                <span class="category" data-category="footwear">Footwear</span>
                <span class="category" data-category="headwear">Headwear</span>
                <span class="category" data-category="electronics">Electronics</span>
        </div>
    </form>
    <div class="post-field">
    <!-- Check if categoryPosts is empty -->
        @if ($categoryPosts->isEmpty())
            <p>No posts found for the selected category.</p>
        @else
            <!-- Display the fetched posts here -->
            @foreach ($categoryPosts as $post)
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
        @endif
    </div>


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

    document.addEventListener('DOMContentLoaded', function () {
        const categorySpans = document.querySelectorAll('.category');

        // Retrieve the selected category from localStorage, if it exists
        const selectedCategory = localStorage.getItem('selectedCategory');

        // Add the 'selected' class to the selected category span
        if (selectedCategory) {
            const selectedSpan = document.querySelector(`.category[data-category="${selectedCategory}"]`);
            if (selectedSpan) {
                selectedSpan.classList.add('selected');
            }
        }

        categorySpans.forEach(span => {
            span.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default behavior of the click event
                
                const category = this.getAttribute('data-category');
                
                // Check if the clicked category is already selected
                const isSelected = this.classList.contains('selected');
                
                // If the clicked category is already selected, remove the 'selected' class
                if (isSelected) {
                    this.classList.remove('selected');
                    localStorage.removeItem('selectedCategory'); // Remove the selected category from localStorage
                } else {
                    // Otherwise, remove the 'selected' class from all category spans
                    categorySpans.forEach(span => {
                        span.classList.remove('selected');
                    });

                    // Add the 'selected' class to the clicked category span
                    this.classList.add('selected');
                    localStorage.setItem('selectedCategory', category); // Store the selected category in localStorage
                }

                const url = `/?category=${category}`;
                window.location.href = url; // Navigate to the constructed URL
            });
        });
    });

</script>


</x-layout>
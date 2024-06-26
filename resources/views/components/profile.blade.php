<x-layout>
    <div class="container py-md-5 container--narrow">
        <div class="mb-3">
            <a href="javascript:location.href = '/'" class="btn btn-dark btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <h2>
            <img class="avatar-small" src="{{$sharedData['avatar']}}" /> {{$sharedData['username']}}
            @auth
                {{-- If you are not following someone, then it displays the follow button--}}
                @if (!$sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['username'])
                    <form class="ml-2 d-inline" action="/create-follow/{{$sharedData['username']}}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif

                {{-- If you are following someone, then it displays the unfollow button--}}
                @if ($sharedData['currentlyFollowing'])
                    <form class="ml-2 d-inline" action="/remove-follow/{{$sharedData['username']}}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif

                @if (auth()->user()->username == $sharedData['username'])
                    <a href="/manage-info" class="btn btn-info btn-sm">Manage Information</a>
                @endif  

            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{$sharedData['username']}}" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "" ? "active" : ""}}">Posts: {{$sharedData['postCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/followers" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "followers" ? "active" : ""}}">Followers: {{$sharedData['followerCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/following" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "following" ? "active" : ""}}">Following: {{$sharedData['followingCount']}}</a>
        </div>

        <div class="profile-slot-content">
            {{$slot}}
        </div>
    </div>
</x-layout>

<div class="card-header bg-white py-2">
    <div class="row align-items-center">
        <div class="col-auto">
            <a href="{{ route('profile.show', $post->user->id) }}">
                @if($post->user->avatar)
                    <img src="{{$post->user->avatar}}" alt="{{$post->user->name}}" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                @endif
            </a>
        </div>
        <div class="col ps-0">
            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-secondary">{{$post->user->name}}</a>
        </div>
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></button>

                <!-- If you are the owner of the post, you can edit and delete this post -->
                <!-- We can use the "Auth::user()->id" to get the id of the use who is currently logged-in -->
                <!-- In LINE 22: It is checking if the id of the user who is currently logged-in is equal to the id of the user who is the owner of the post -->
                @if(Auth::user()->id === $post->user->id)
                    <div class="dropdown-menu">
                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                            <!-- Fontawesome icon -->
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </a>
                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}"><i class="fa-regular fa-trash-can"></i> Delete</button>
                    </div>
                    <!-- Include/Insert Modal Here -->
                    @include('users.posts.contents.modals.delete')
                @else
                    <!-- If you are not the owner of the post, show the unfollow button -->
                    <div class="dropdown-menu">
                        <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">Unfollow</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

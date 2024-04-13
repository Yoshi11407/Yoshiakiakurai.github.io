@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4{
            overflow: scroll;
        }

        .card-body{
            position: absolute;
            top: 65px;
        }
    </style>

    {{-- $post --- with data  --}}
    <div class="row border shadow">
        <div class="col p-0 border-end">
            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
        </div>
        <div class="col-4 bg-white">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    {{-- Fontawesome icon --}}
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                        </div>
                        <div class="col-auto">

                            @if (Auth::user()->id === $post->user->id)
                                {{-- Display edit and delete button --}}
                                <div class="dropdown">
                                    <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                            <!-- Fontawesome icon -->
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{$post->id}}">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                        <!-- Include/Insert Modal Here -->
                                        @include('users.posts.contents.modals.delete')
                                </div>
                            @else
                                {{-- We will display Follow/Unfollow button here --}}
                                @if ($post->user->isFollowed())
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-secondary">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $post->user->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="border-0 bg-transparent text-primary">Follow</button>
                                    </form>
                                @endif


                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body w-100">
                    <!-- Heart button + no. of likes + categories -->
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if ($post->isLiked())
                                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('like.store', $post->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                        <div class="col-auto px-0">
                            <span>{{ $post->likes->count() }}</span>
                        </div>
                        <div class="col text-end">
                            <!-- Loop to all the category of the post and display it -->
                            @foreach($post->categoryPost as $category_post)
                                <div class="badge bg-secondary bg-opacity-50">
                                    {{ $category_post->category->name }}
                                    <!-- $category_post: is use to get the categories of a post  -->
                                    <!-- category: this is the method name we have in CategoryPost.php... it is use to retrieved the name of the categories  -->
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <!-- Display the owner of the post and description of the post -->
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $post->description }}</p>
                    <p class="text-muted text-uppercase xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>
                    <p class="text-danger xsmall">Posted {{ $post->created_at->diffForHumans() }}</p>

                    {{-- Show all the comments here --}}
                    @if ($post->comments->isNotEmpty())
                        <ul class="list-group mt-2">
                            @foreach ($post->comments as $comment)
                                <li class="list-group-item border-0 mb-2">
                                    <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                    &nbsp;
                                    <p class="d-inline fw-light">{{ $comment->body }}</p>
                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>
                                        <p class="text-danger xsmall">Posted {{ $comment->created_at->diffForHumans() }}</p>

                                        {{-- If the auth user is THE OWNER OF THE POST, then show
                                            a DELETE button --}}
                                        @if (Auth::user()->id === $comment->user->id)
                                            &middot;
                                            <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                        @endif
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <form action="{{ route('comment.store', $post->id) }}" method="post">
                        @csrf
                        <div class="input-group">
                            {{-- Note: comment_body{{ $post->id }} -- we have to attach the ID of the post to specify which post is the comment for   --}}
                            <textarea name="comment_body{{ $post->id }}" id="" rows="1" class="form-control form-control-sm" placeholder="Add a comment">{{ old('comment_body' . $post->id) }}</textarea>
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                        </div>
                        {{-- Error Message Area --}}
                        @error('comment_body' . $post->id)
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

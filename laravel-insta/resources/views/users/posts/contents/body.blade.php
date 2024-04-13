<!-- Clickable image -->
<div class="container p-0">
    <a href="{{route('post.show', $post->id)}}">
        <!-- Display the image in the homepage -->
        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
    </a>
</div>
<div class="card-body">
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
    <p class="mb-0 text-muted text-uppercase xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>
    <p class="p-0 m-0 xsmall">Posted {{ $post->created_at->diffForHumans() }}</p>

    {{-- Insert Comments Here --}}
    @include('users.posts.contents.comments')
</div>

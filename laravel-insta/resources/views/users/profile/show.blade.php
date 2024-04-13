@extends('layouts.app')

@section('title', $user->name)

@section('content')
    @include('users.profile.header')

    {{-- Show all the posts of a user here --}}
    <div style="margin-top: 100px">
        {{-- Check if the user has posts --}}
        @if ($user->posts->isNotEmpty())
            {{-- if the user has posts --}}
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $post->id) }}"><img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img"></a>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-muted text-center">No Post Yet.</h3>
        @endif
    </div>
@endsection

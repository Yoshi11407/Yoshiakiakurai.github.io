@extends('layouts.app')

@section('title', 'Followers')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        @if ($user->followers->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-4">
                    <h3 class="text-muted text-center">Followers</h3>

                    @foreach ( $user->followers as $follower)
                        <div class="row align-items-center mt-3">
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $follower->follower->id) }}">
                                    {{-- Check if the user has avatar/profile image or not --}}
                                    @if ($follower->follower->avatar)
                                        {{-- Display the avatar if available --}}
                                        <img src="{{ $follower->follower->avatar }}" alt="" class="rounded-circle avatar-sm">
                                    @else
                                        {{-- If not available, then display a fontawesome icon --}}
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="col ps-auto text-truncate">
                                {{-- Display the user name of the user --}}
                                <a href="{{ route('profile.show', $follower->follower->id) }}" class="text-decoration-none text-dark fw-bold">{{ $follower->follower->name }}</a>
                            </div>
                            <div class="col-auto text-end">
                                @if ($follower->follower->id != Auth::user()->id)
                                    @if ($follower->follower->isFollowed())
                                        <form action="{{ route('follow.destroy', $follower->follower->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent p-0 text-secondary btn-sm">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $follower->follower->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <h3 class="text-muted text-center">No Follower Yet</h3>
        @endif
    </div>

@endsection

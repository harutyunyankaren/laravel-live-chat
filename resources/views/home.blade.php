@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="single">
                @if(count($rooms))
                <h3 class="side-title">Chat rooms list</h3>
                <ul class="list-unstyled">
                    @foreach($rooms as $room)
                    <li>
                        <div class="row">
                            <div class="col-xs-4 mt-8">
                                {{ $room->name }}
                            </div>
                            <div class="col-xs-3 mt-8">
                                Online Users&nbsp;<span class="badge badge-primary badge-pill">{{ $room->online_users_count }}</span>
                            </div>
                            <div class="col-xs-3 mt-8">
                                @if($room->is_private) Private room @else Public room @endif
                            </div>
                            <div class="col-xs-2 text-right">
                            @if((Auth::check() && $room->is_private && Auth::id() == $room->created_by) || !$room->is_private)
                                <a href="{{ asset("/chat-room/$room->id") }}">Join</a>
                            @elseif(Auth::check() && $room->is_private && Auth::id() != $room->created_by)
                                <div class="mt-8">Get key</div>
                            @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                    <span>No chat rooms</span>
                @endif
            </div>
        </div>

        @auth
        <div class="col-sm-4">
            <div class="single">
                <form action="/store-room" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="room-name">Create new chat room</label>
                        <input type="text" name="room_name" class="form-control" id="room-name" placeholder="Chat Room Name">
                        @if($errors->has('name')) <span class="text-danger">{{ $errors->first('name') }}</span> @endif
                    </div>

                    <div class="form-group">
                        <input type="file" id="image" name="image"/>
                        @if($errors->has('image')) <span class="text-danger">{{ $errors->first('image') }}</span> @endif
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_private" class="form-check-input" id="private-room" value="1">
                        <label class="form-check-label" for="private-room">Private chat room</label>
                    </div>

                    <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
        @endauth
        </div>
    </div>
@endsection

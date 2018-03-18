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
                            <div class="col-xs-2">
                                <img src="{{ asset("/storage/images/$room->image") }}" alt="" width="50px" height="50px">
                            </div>
                            <div class="col-xs-4 mt-12">
                                {{ $room->name }}
                            </div>
                            <div class="col-xs-4 mt-12">
                                @if($room->is_private) Private room @else Public room @endif
                            </div>
                            <div class="col-xs-2 text-right mt-8">
                            @if((Auth::check() && $room->is_private && Auth::id() == $room->created_by) || !$room->is_private)
                                <a href="{{ asset("/chat-room/$room->id") }}">Join</a>
                            @elseif(Auth::check() && $room->is_private && Auth::id() != $room->created_by)
                                <div class="get-key">
                                    <a href="#" data-toggle="modal" data-target="#modal">Join</a>
                                </div>
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
                        <input type="text" name="name" class="form-control" id="room-name" placeholder="Chat Room Name">
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

    <!-- Modal -->
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Go To Private Chat Roon</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="link">Complete the link:</label>
                        <input type="text" name="link" class="form-control" id="link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="join" data-dismiss="modal">Join</button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection



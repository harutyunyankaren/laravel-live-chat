@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $room->name }} <span class="pull-right"> Members Count: {{ $room->members_count }}</span></div>

                <div class="panel-body">
                    <chat-messages :messages="messages"></chat-messages>
                    {{--<ul class="chat">--}}
                        {{--@foreach($messages as $message)--}}
                            {{--<li class="left clearfix">--}}
                                {{--<div class="chat-body clearfix">--}}
                                    {{--<div class="header">--}}
                                        {{--<strong class="primary-font">--}}
                                            {{--{{ $message->sender_name }}--}}
                                        {{--</strong>--}}
                                    {{--</div>--}}
                                    {{--<p>--}}
                                        {{--{{ $message->message }}--}}
                                    {{--</p>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                        {{--@endforeach--}}
                    {{--</ul>--}}
                </div>
                <div class="panel-footer">
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ $user }}"
                    ></chat-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use App\Http\Requests\ChatRoomRequest;
use App\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Keygen\Keygen;


class ChatRoomController extends Controller
{
    /**
     * Show chat rooms
     *
     * @return \Illuminate\Http\Response
     */
    public function index($room_id)
    {
        $room = ChatRoom::find($room_id);

        if ($room == null) {
            return redirect('/');
        }

        if (Auth::check()) {
            $user = Auth::user();
        }
        else {
            $name = 'Guest';
            $user = collect(['name' => $name]);
        }

        return view('chat', compact('room', 'user'));
    }

    /**
     * Create Chat room
     *
     * @param ChatRoomRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ChatRoomRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $data['image'];
            $image_name = time() . '.' . str_replace(' ', '', $image->getClientOriginalname());
            $destinationPath = public_path('/storage/images');
            $image->move($destinationPath, $image_name);
        }

        if (isset($data['is_private']) && !empty($data['is_private'])) {
            $key = asset(Keygen::alphanum(15)->generate());
            $is_private = 1;
        }

        $room = new ChatRoom();
        $room->created_by = $data['created_by'];
        $room->name = $data['name'];
        $room->image = $image_name ?? '';
        $room->is_private = $is_private ?? 0;
        $room->link = $key ?? null;

        $room->save();

        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function goToPrivateChatRoom(Request $request)
    {
        $room = ChatRoom::where('link', $request->url())->first();
        return redirect("/chat-room/$room->id");
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($id)
    {
        return Message::where('chat_room_id', $id)->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage($room_id, Request $request)
    {
        $sender_name = $request->input('sender_name');

        $message = Message::create([
            'message' => $request->input('message'),
            'chat_room_id' => $room_id,
            'sender_name' => $sender_name
        ]);

        broadcast(new MessageSent($sender_name, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}

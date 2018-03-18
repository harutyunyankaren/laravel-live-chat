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
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index($room_id)
    {
//        $this->updateMembersCount($room_id);
        $room = ChatRoom::find($room_id);

        if ($room == null) {
            return redirect('/');
        }

        if (Auth::check()) {
            $user = Auth::user();
        }
        else {
            $members_count = $room->members_count;
            $name = 'Guest-'. ($members_count + 1);
            $user = collect(['name' => $name]);
        }

//        $this->updateUsersList($room_id, $user->name);
        return view('chat', compact('room_id', 'room', 'user'));
    }

    public function store(ChatRoomRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $data['image'];
            $image_name = time() . '.' . str_replace(' ', '', $image->getClientOriginalname());
            $destinationPath = storage_path('/images');
            $image->move($destinationPath, $image_name);
        }

        if (isset($data['is_private']) && !empty($data['is_private'])) {
            $key = Keygen::alphanum(12)->generate();
            $is_private = 1;
        }

        $room = new ChatRoom();
        $room->created_by = $data['created_by'];
        $room->name = $data['room_name'];
        $room->image = $image_name ?? '';
        $room->is_private = $is_private ?? 0;
        $room->link = $key ?? null;

        $room->save();

        return back();
    }

    public function goToPrivateChatRoom($key)
    {
        $room = ChatRoom::where('link', $key)->first();
        return redirect("/chat-room/$room->id");
    }

    public function updateMembersCount($room_id)
    {
        $room = ChatRoom::find($room_id);
        $room->members_count += 1;
        $room->update();
    }

    public function updateUsersList($room_id, $name)
    {
        $room = ChatRoom::find($room_id);
//        if ($room->users_list == null) {
        $room->users_list = $name;
        $room->update();
//        }
        dd($room->users_list);
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

//        $user = Auth::user();
//        $message = $user->messages()->create([
        $message = Message::create([
            'message' => $request->input('message'),
            'chat_room_id' => $room_id,
            'sender_name' => $sender_name
        ]);


        broadcast(new MessageSent($sender_name, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}

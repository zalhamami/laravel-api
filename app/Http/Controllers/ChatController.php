<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatEvent;
use App\Repositories\ChatRepository;
use App\User;
use Illuminate\Http\Request;

class ChatController extends ApiController
{
    public function __construct(ChatRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function index()
    {
        $data = $this->repo->getAll();
        return $this->collectionData($data);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(1);

        $chat = $user->chats()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new ChatEvent($chat, $user))->toOthers();

        return [
            'chat' => $chat,
            'user' => $user,
        ];
    }
}

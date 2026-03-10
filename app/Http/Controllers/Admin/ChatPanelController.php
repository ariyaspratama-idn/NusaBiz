<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatPanelController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->orderByDesc('last_activity_at')
            ->paginate(20);

        $totalUnread = ChatSession::with('messages')
            ->get()
            ->sum(fn($s) => $s->unreadCount());

        return view('admin.chat.index', compact('sessions', 'totalUnread'));
    }

    public function show(ChatSession $session)
    {
        $session->messages()->where('sender_type', 'visitor')->update(['is_read' => true]);
        $messages = $session->messages()->with('admin')->orderBy('created_at')->get();
        return view('admin.chat.show', compact('session', 'messages'));
    }

    public function getMessages(Request $request, ChatSession $session)
    {
        $lastId = $request->query('after_id', 0);
        $messages = $session->messages()
            ->with('admin')
            ->where('id', '>', $lastId)
            ->orderBy('created_at')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('H:i'),
                    'admin_name' => $msg->admin->name ?? null,
                ];
            });

        // Mark as read if it's from visitor
        $session->messages()->where('id', '>', $lastId)->where('sender_type', 'visitor')->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function reply(Request $request, ChatSession $session)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $msg = ChatMessage::create([
            'session_id'  => $session->id,
            'sender_type' => 'admin',
            'admin_id'    => auth()->id(),
            'message'     => $request->message,
        ]);

        $session->update(['last_activity_at' => now()]);

        // Broadcast via Reverb (WebSocket) – event akan dibuat nanti
        // event(new \App\Events\AdminReplied($msg));

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $msg->id,
                'message'    => $msg->message,
                'created_at' => $msg->created_at->format('H:i'),
                'admin_name' => auth()->user()->name,
            ],
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['is_online' => 'required|boolean']);
        \App\Models\ChatSetting::updateOrCreate([], ['is_online' => $request->is_online]);
        return response()->json(['success' => true]);
    }
}

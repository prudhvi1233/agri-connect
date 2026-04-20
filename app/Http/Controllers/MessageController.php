<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display all conversations for the buyer
     */
    public function index()
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Access denied.');
        }

        // Get all contracts with messages
        $contracts = Contract::where('buyer_id', Auth::id())
            ->with(['farmer', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->has('messages')
            ->orderByDesc('updated_at')
            ->get();

        return view('messages.index', compact('contracts'));
    }

    /**
     * Show conversation for a specific contract
     */
    public function show(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        $contract->load(['farmer', 'buyer', 'messages.sender']);

        // Mark messages as read (we'll add this field later if needed)
        
        return view('messages.show', compact('contract'));
    }

    /**
     * Send a new message
     */
    public function store(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages/attachments', 'public');
        }

        Message::create([
            'contract_id' => $contract->id,
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
            'attachment' => $attachmentPath,
        ]);

        // Update contract's updated_at timestamp
        $contract->touch();

        // TODO: Send notification to the other party
        // This could be an email, push notification, etc.

        return redirect()->route('messages.show', $contract)
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Download message attachment
     */
    public function downloadAttachment(Message $message)
    {
        $contract = $message->contract;
        
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if (!$message->attachment) {
            abort(404);
        }

        return Storage::download('public/' . $message->attachment);
    }

    /**
     * Display all conversations for the farmer
     */
    public function farmerIndex()
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        // Get all contracts with messages for this farmer
        $contracts = Contract::where('farmer_id', Auth::id())
            ->with(['buyer', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->has('messages')
            ->orderByDesc('updated_at')
            ->get();

        return view('farmer.messages.index', compact('contracts'));
    }

    /**
     * Show conversation for a specific contract (farmer)
     */
    public function farmerShow(Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        $contract->load(['buyer', 'messages.sender']);

        return view('farmer.messages.show', compact('contract'));
    }

    /**
     * Send a new message (farmer)
     */
    public function farmerStore(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages/attachments', 'public');
        }

        Message::create([
            'contract_id' => $contract->id,
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
            'attachment' => $attachmentPath,
        ]);

        // Update contract's updated_at timestamp
        $contract->touch();

        // TODO: Send notification to buyer
        // This could be an email, push notification, etc.

        return redirect()->route('farmer.messages.show', $contract)
            ->with('success', 'Message sent successfully.');
    }
}

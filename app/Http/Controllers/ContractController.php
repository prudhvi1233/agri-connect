<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    /**
     * Display all contracts for the logged-in buyer
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Access denied.');
        }

        $query = Contract::where('buyer_id', Auth::id())
            ->with(['farmer', 'listing']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('crop_name', 'like', "%{$search}%")
                  ->orWhereHas('farmer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by date
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $contracts = $query->paginate(10)->withQueryString();

        // Get status counts for badges
        $statusCounts = [
            'all' => Contract::where('buyer_id', Auth::id())->count(),
            'pending' => Contract::where('buyer_id', Auth::id())->where('status', 'pending')->count(),
            'active' => Contract::where('buyer_id', Auth::id())->whereIn('status', ['signed', 'active'])->count(),
            'completed' => Contract::where('buyer_id', Auth::id())->where('status', 'completed')->count(),
            'rejected' => Contract::where('buyer_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('contracts.index', compact('contracts', 'statusCounts'));
    }

    /**
     * Show the create contract form for buyers
     */
    public function create()
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Only buyers can create contracts.');
        }

        $farmers = User::where('role', 'farmer')->orderBy('name')->get();
        
        return view('contracts.create', compact('farmers'));
    }

    /**
     * Store a newly created contract in database
     */
    public function createContract(Request $request)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Only buyers can create contracts.');
        }

        $validated = $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'crop_name' => 'required|string|max:255',
            'quantity_required' => 'required|numeric|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'delivery_date' => 'required|date|after:today',
            'delivery_location' => 'required|string|max:255',
            'advance_percentage' => 'required|numeric|min:0|max:100',
            'contract_start_date' => 'required|date|before:contract_end_date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'additional_terms' => 'nullable|string',
        ]);

        // Calculate total amount and payment details
        $totalAmount = $validated['quantity_required'] * $validated['price_per_unit'];
        $advanceAmount = ($totalAmount * $validated['advance_percentage']) / 100;
        $finalPayment = $totalAmount - $advanceAmount;

        $contract = Contract::create([
            'buyer_id' => Auth::id(),
            'farmer_id' => $validated['farmer_id'],
            'listing_id' => null, // Direct contract without listing
            'crop_name' => $validated['crop_name'],
            'agreed_price' => $totalAmount,
            'price_per_unit' => $validated['price_per_unit'],
            'agreed_quantity' => $validated['quantity_required'],
            'total_amount' => $totalAmount,
            'delivery_date' => $validated['delivery_date'],
            'delivery_location' => $validated['delivery_location'],
            'payment_terms' => $validated['advance_percentage'] . '% advance, ' . (100 - $validated['advance_percentage']) . '% on delivery',
            'advance_percentage' => $validated['advance_percentage'],
            'advance_amount' => $advanceAmount,
            'final_payment' => $finalPayment,
            'contract_start_date' => $validated['contract_start_date'],
            'contract_end_date' => $validated['contract_end_date'],
            'additional_terms' => $validated['additional_terms'],
            'status' => 'pending',
        ]);

        // TODO: Send notification to farmer (will be implemented)
        // This could be an email, in-app notification, etc.

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract created successfully and sent to farmer for approval.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'agreed_price' => 'required|numeric|min:0',
            'agreed_quantity' => 'required|numeric|min:1',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        if (Auth::user()->role !== 'buyer') abort(403);

        $contract = Contract::create([
            'buyer_id' => Auth::id(),
            'farmer_id' => $listing->farmer_id,
            'listing_id' => $listing->id,
            'agreed_price' => $request->agreed_price,
            'agreed_quantity' => $request->agreed_quantity,
            'status' => 'proposed',
        ]);

        return redirect()->route('contracts.show', $contract)->with('success', 'Contract proposed successfully.');
    }

    public function show(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) abort(403);

        $contract->load(['buyer', 'farmer', 'listing', 'messages.sender', 'payments']);

        // Ensure all messages have sender loaded
        foreach ($contract->messages as $message) {
            if (!$message->sender) {
                $message->load('sender');
            }
        }

        return view('contracts.show', compact('contract'));
    }

    public function negotiate(Request $request, Contract $contract)
    {
        // Allow both buyer and farmer to send messages
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) abort(403);
        
        // Only prevent messaging if contract is cancelled
        if ($contract->status === 'cancelled') {
            return redirect()->route('contracts.show', $contract)
                ->with('error', 'Cannot send messages for cancelled contracts.');
        }

        $request->validate([
            'body' => 'required|string',
            'suggested_price' => 'nullable|numeric|min:0',
            'suggested_quantity' => 'nullable|numeric|min:1',
        ]);

        // Create the message
        Message::create([
            'contract_id' => $contract->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Update contract terms and status if new terms are proposed
        // Only allow term proposals during negotiation phase
        if ($request->suggested_price || $request->suggested_quantity) {
            if (in_array($contract->status, ['pending', 'proposed', 'negotiating', 'signed', 'active'])) {
                $contract->update([
                    'agreed_price' => $request->suggested_price ?? $contract->agreed_price,
                    'agreed_quantity' => $request->suggested_quantity ?? $contract->agreed_quantity,
                    'status' => 'negotiating',
                ]);
            }
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'Message sent successfully.');
    }

    public function sign(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) abort(403);

        $contract->update(['status' => 'signed']);

        // Option: Change listing status to contracted
        if ($contract->listing) {
            $contract->listing->update(['status' => 'contracted']);
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'Contract signed and activated.');
    }

    /**
     * Reject/Cancel a contract
     */
    public function destroy(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403);
        }

        $contract->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')->with('success', 'Contract rejected successfully.');
    }

    /**
     * Show the edit form for a pending contract
     */
    public function edit(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id) {
            abort(403, 'Access denied.');
        }

        if ($contract->status !== 'pending') {
            return redirect()->route('contracts.index')
                ->with('error', 'Only pending contracts can be edited.');
        }

        $farmers = User::where('role', 'farmer')->orderBy('name')->get();

        return view('contracts.edit', compact('contract', 'farmers'));
    }

    /**
     * Update a pending contract
     */
    public function update(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id) {
            abort(403, 'Access denied.');
        }

        if ($contract->status !== 'pending') {
            return redirect()->route('contracts.index')
                ->with('error', 'Only pending contracts can be updated.');
        }

        $validated = $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'crop_name' => 'required|string|max:255',
            'quantity_required' => 'required|numeric|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'delivery_date' => 'required|date|after:today',
            'delivery_location' => 'required|string|max:255',
            'advance_percentage' => 'required|numeric|min:0|max:100',
            'contract_start_date' => 'required|date|before:contract_end_date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'additional_terms' => 'nullable|string',
        ]);

        // Calculate total amount and payment details
        $totalAmount = $validated['quantity_required'] * $validated['price_per_unit'];
        $advanceAmount = ($totalAmount * $validated['advance_percentage']) / 100;
        $finalPayment = $totalAmount - $advanceAmount;

        $contract->update([
            'farmer_id' => $validated['farmer_id'],
            'crop_name' => $validated['crop_name'],
            'agreed_price' => $totalAmount,
            'price_per_unit' => $validated['price_per_unit'],
            'agreed_quantity' => $validated['quantity_required'],
            'total_amount' => $totalAmount,
            'delivery_date' => $validated['delivery_date'],
            'delivery_location' => $validated['delivery_location'],
            'payment_terms' => $validated['advance_percentage'] . '% advance, ' . (100 - $validated['advance_percentage']) . '% on delivery',
            'advance_percentage' => $validated['advance_percentage'],
            'advance_amount' => $advanceAmount,
            'final_payment' => $finalPayment,
            'contract_start_date' => $validated['contract_start_date'],
            'contract_end_date' => $validated['contract_end_date'],
            'additional_terms' => $validated['additional_terms'],
        ]);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    /**
     * Cancel a contract (buyer only, if pending)
     */
    public function cancel(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($contract->status, ['pending', 'proposed'])) {
            return redirect()->route('contracts.index')
                ->with('error', 'Only pending contracts can be cancelled.');
        }

        $contract->update(['status' => 'cancelled']);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract cancelled successfully.');
    }

    /**
     * Download contract agreement as PDF
     */
    public function download(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($contract->status, ['signed', 'active', 'completed'])) {
            return redirect()->route('contracts.index')
                ->with('error', 'Only active contracts can be downloaded.');
        }

        // For now, we'll create a simple HTML view that can be printed/saved as PDF
        // In production, you'd use a package like dompdf or barryvdh/laravel-dompdf
        $contract->load(['buyer', 'farmer', 'listing']);
        
        return view('contracts.pdf', compact('contract'));
    }

    /**
     * Display contract requests for farmers (pending contracts)
     */
    public function farmerRequests(Request $request)
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        $query = Contract::where('farmer_id', Auth::id())
            ->with(['buyer', 'listing']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('crop_name', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by date
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $requests = $query->paginate(10)->withQueryString();

        // Get status counts
        $statusCounts = [
            'all' => Contract::where('farmer_id', Auth::id())->count(),
            'pending' => Contract::where('farmer_id', Auth::id())->where('status', 'pending')->count(),
            'accepted' => Contract::where('farmer_id', Auth::id())->whereIn('status', ['signed', 'active'])->count(),
            'rejected' => Contract::where('farmer_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('farmer.contract-requests', compact('requests', 'statusCounts'));
    }

    /**
     * Display my contracts for farmers (accepted/completed)
     */
    public function farmerContracts(Request $request)
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        $query = Contract::where('farmer_id', Auth::id())
            ->whereIn('status', ['signed', 'active', 'completed'])
            ->with(['buyer', 'listing', 'payments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('crop_name', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $contracts = $query->paginate(10)->withQueryString();

        return view('farmer.my-contracts', compact('contracts'));
    }

    
    /**
     * Accept a contract (farmer)
     */
    public function accept(Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if ($contract->status !== 'pending') {
            return redirect()->route('farmer.contract-requests')
                ->with('error', 'Only pending contracts can be accepted.');
        }

        $contract->update(['status' => 'signed']);

        // TODO: Send notification to buyer
        // This could be email, in-app notification, etc.

        return redirect()->route('farmer.contract-requests')
            ->with('success', 'Contract accepted successfully. Buyer has been notified.');
    }

    /**
     * Reject a contract (farmer)
     */
    public function reject(Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if ($contract->status !== 'pending') {
            return redirect()->route('farmer.contract-requests')
                ->with('error', 'Only pending contracts can be rejected.');
        }

        $contract->update(['status' => 'cancelled']);

        return redirect()->route('farmer.contract-requests')
            ->with('success', 'Contract rejected successfully.');
    }

    /**
     * Request modification for a contract (farmer)
     */
    public function requestModification(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if ($contract->status !== 'pending') {
            return redirect()->route('farmer.contract-requests')
                ->with('error', 'Only pending contracts can be modified.');
        }

        $validated = $request->validate([
            'modification_notes' => 'required|string|max:1000',
        ]);

        // Send message to buyer with modification request
        Message::create([
            'contract_id' => $contract->id,
            'sender_id' => Auth::id(),
            'body' => 'Modification Request: ' . $validated['modification_notes'],
        ]);

        return redirect()->route('farmer.contract-requests')
            ->with('success', 'Modification request sent to buyer.');
    }

    /**
     * Update delivery status for a contract (farmer)
     */
    public function updateDeliveryStatus(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($contract->status, ['signed', 'active', 'negotiating', 'completed'])) {
            return redirect()->back()
                ->with('error', 'Cannot update delivery status for this contract.');
        }

        $validated = $request->validate([
            'delivery_status' => 'required|in:processing,preparing,ready,shipped,delivered',
        ]);

        // Update the database with the new actual delivery status
        $contract->update([
            'delivery_status' => $validated['delivery_status']
        ]);

        // If delivery is marked as delivered, update contract status to completed as well
        if ($validated['delivery_status'] === 'delivered') {
            $contract->update([
                'status' => 'completed',
            ]);

            // Send notification message
            Message::create([
                'contract_id' => $contract->id,
                'sender_id' => Auth::id(),
                'body' => '✅ Product has been delivered successfully. Contract marked as completed.',
            ]);

            return redirect()->back()
                ->with('success', 'Delivery marked as completed. Contract is now fully closed.');
        }

        return redirect()->back()
            ->with('success', 'Delivery status updated to: ' . ucfirst($validated['delivery_status']));
    }

    /**
     * Mark contract as completed (buyer confirmation)
     */
    public function markCompleted(Contract $contract)
    {
        // Both buyer and farmer can mark as completed
        if (Auth::id() !== $contract->buyer_id && Auth::id() !== $contract->farmer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($contract->status, ['signed', 'active', 'negotiating'])) {
            return redirect()->route('contracts.show', $contract)
                ->with('error', 'Cannot complete this contract.');
        }

        $contract->update([
            'status' => 'completed',
        ]);

        // Send notification message
        Message::create([
            'contract_id' => $contract->id,
            'sender_id' => Auth::id(),
            'body' => '✅ Contract marked as completed by ' . Auth::user()->name . '. Thank you!',
        ]);

        $redirectRoute = Auth::user()->role === 'farmer' 
            ? route('farmer.my-contracts') 
            : route('contracts.index');

        return redirect($redirectRoute)
            ->with('success', 'Contract marked as completed successfully.');
    }
}

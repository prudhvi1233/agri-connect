<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display payment history for the buyer
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Access denied.');
        }

        $query = Payment::whereHas('contract', function($q) {
                $q->where('buyer_id', Auth::id());
            })
            ->with(['contract.farmer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('type')) {
            $query->where('payment_type', $request->type);
        }

        $payments = $query->paginate(10)->withQueryString();

        // Calculate total paid
        $totalPaid = Payment::whereHas('contract', function($q) {
                $q->where('buyer_id', Auth::id());
            })
            ->where('status', 'completed')
            ->sum('amount');

        return view('payments.index', compact('payments', 'totalPaid'));
    }

    /**
     * Show payment form for a contract
     */
    public function create(Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($contract->status, ['signed', 'active'])) {
            return redirect()->route('payments.index')
                ->with('error', 'Payment not available for this contract.');
        }

        $contract->load('payments');

        // Calculate remaining amounts
        $totalPaid = $contract->payments->where('status', 'completed')->sum('amount');
        $advanceAmount = $contract->advance_amount ?? 0;
        $finalPayment = $contract->final_payment ?? 0;
        $advancePaid = $contract->payments->where('payment_type', 'advance')->where('status', 'completed')->sum('amount');
        $finalPaid = $contract->payments->where('payment_type', 'final')->where('status', 'completed')->sum('amount');

        return view('payments.create', compact('contract', 'totalPaid', 'advanceAmount', 'finalPayment', 'advancePaid', 'finalPaid'));
    }

    /**
     * Process payment
     */
    public function store(Request $request, Contract $contract)
    {
        if (Auth::id() !== $contract->buyer_id) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,credit_card,debit_card,upi,cash',
            'payment_type' => 'required|in:advance,final,full',
            'card_number' => 'nullable|required_if:payment_method,credit_card,debit_card|string|max:19',
            'upi_id' => 'nullable|required_if:payment_method,upi|string|max:255',
        ]);

        // Simulate payment processing
        $transactionId = 'TXN' . strtoupper(Str::random(10)) . time();

        $payment = Payment::create([
            'contract_id' => $contract->id,
            'transaction_id' => $transactionId,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_type' => $validated['payment_type'],
            'payment_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment of ₹' . number_format($validated['amount'], 2) . ' is processing and pending farmer confirmation. Transaction ID: ' . $transactionId);
    }

    /**
     * Confirm payment receipt (farmer updates status)
     */
    public function confirm(Request $request, Payment $payment)
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        $payment->load('contract');
        
        if ($payment->contract->farmer_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment is already processed.');
        }

        $payment->update(['status' => 'completed']);
        $contract = $payment->contract;

        // Update contract status if first payment
        if ($contract->status === 'signed' && in_array($payment->payment_type, ['advance', 'full'])) {
            $contract->update(['status' => 'active']);
        }

        // Check if fully paid
        $totalPaid = $contract->payments()->where('status', 'completed')->sum('amount');
        if ($totalPaid >= ($contract->total_amount ?? $contract->agreed_price)) {
            $contract->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', 'Payment marked as received successfully.');
    }

    /**
     * Display payment history for the farmer
     */
    public function farmerPayments(Request $request)
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        $query = Payment::whereHas('contract', function($q) {
                $q->where('farmer_id', Auth::id());
            })
            ->with(['contract.buyer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('type')) {
            $query->where('payment_type', $request->type);
        }

        $payments = $query->paginate(10)->withQueryString();

        // Calculate summary
        $totalEarnings = Payment::whereHas('contract', function($q) {
                $q->where('farmer_id', Auth::id());
            })
            ->where('status', 'completed')
            ->sum('amount');

        $completedPayments = Payment::whereHas('contract', function($q) {
                $q->where('farmer_id', Auth::id());
            })
            ->where('status', 'completed')
            ->count();

        $pendingPayments = Payment::whereHas('contract', function($q) {
                $q->where('farmer_id', Auth::id());
            })
            ->where('status', 'pending')
            ->count();

        return view('farmer.payments', compact('payments', 'totalEarnings', 'completedPayments', 'pendingPayments'));
    }
}

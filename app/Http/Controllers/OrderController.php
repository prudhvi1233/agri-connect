<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display all orders for the logged-in buyer
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403, 'Access denied.');
        }

        // Orders are contracts with status 'active', 'signed', or 'completed'
        $query = Contract::where('buyer_id', Auth::id())
            ->whereIn('status', ['signed', 'active', 'completed'])
            ->with(['farmer', 'listing', 'payments']);

        // Filter by delivery status
        if ($request->filled('delivery_status')) {
            if ($request->delivery_status === 'pending') {
                $query->where('delivery_date', '>=', now()->toDateString());
            } elseif ($request->delivery_status === 'delivered') {
                $query->where('delivery_date', '<', now()->toDateString());
            }
        }

        // Filter by contract status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('crop_name', 'like', "%{$search}%")
                  ->orWhereHas('farmer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Contract $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($order->status, ['signed', 'active', 'completed'])) {
            return redirect()->route('orders.index')
                ->with('error', 'Invalid order.');
        }

        $order->load(['farmer', 'listing', 'payments']);

        return view('orders.show', compact('order'));
    }

    /**
     * Download invoice for an order
     */
    public function downloadInvoice(Contract $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($order->status, ['signed', 'active', 'completed'])) {
            return redirect()->route('orders.index')
                ->with('error', 'Invoice not available for this order.');
        }

        $order->load(['farmer', 'listing', 'payments']);

        return view('orders.invoice', compact('order'));
    }

    /**
     * Display orders for the logged-in farmer
     */
    public function farmerOrders(Request $request)
    {
        if (Auth::user()->role !== 'farmer') {
            abort(403, 'Access denied.');
        }

        // Orders are contracts with status 'active', 'signed', or 'completed' for this farmer
        $query = Contract::where('farmer_id', Auth::id())
            ->whereIn('status', ['signed', 'active', 'completed'])
            ->with(['buyer', 'listing', 'payments']);

        // Filter by delivery status
        if ($request->filled('delivery_status')) {
            if ($request->delivery_status === 'pending') {
                $query->where('delivery_date', '>=', now()->toDateString());
            } elseif ($request->delivery_status === 'delivered') {
                $query->where('delivery_date', '<', now()->toDateString());
            }
        }

        // Filter by contract status
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

        $orders = $query->paginate(10)->withQueryString();

        return view('farmer.orders', compact('orders'));
    }

    /**
     * Show order details for farmer
     */
    public function farmerOrderShow(Contract $order)
    {
        if (Auth::id() !== $order->farmer_id) {
            abort(403, 'Access denied.');
        }

        if (!in_array($order->status, ['signed', 'active', 'completed'])) {
            return redirect()->route('farmer.orders')
                ->with('error', 'Invalid order.');
        }

        $order->load(['buyer', 'listing', 'payments']);

        return view('farmer.order-show', compact('order'));
    }
}

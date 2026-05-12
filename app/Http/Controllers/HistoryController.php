<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.menu', 'payment'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default: today
            $query->whereDate('created_at', today());
        }

        $orders = $query->paginate(20)->withQueryString();

        // Summary stats for selected date/filter
        $statsQuery = Order::query();
        if ($request->filled('date')) {
            $statsQuery->whereDate('created_at', $request->date);
        } else {
            $statsQuery->whereDate('created_at', today());
        }

        $stats = [
            'total_orders'    => $statsQuery->count(),
            'completed'       => (clone $statsQuery)->where('status', 'completed')->count(),
            'processing'      => (clone $statsQuery)->whereIn('status', ['processing', 'ready'])->count(),
            'cancelled'       => (clone $statsQuery)->where('status', 'cancelled')->count(),
            'total_revenue'   => (clone $statsQuery)->where('status', 'completed')->sum('total_amount'),
        ];

        return view('kasir.history', compact('orders', 'stats'));
    }
}

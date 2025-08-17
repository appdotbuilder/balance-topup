<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TopupRequest;
use App\Models\BalanceHistory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->limit(5)
            ->get();

        // Get recent top-ups
        $recentTopups = TopupRequest::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // Get balance history (with try-catch for missing table)
        try {
            $balanceHistory = BalanceHistory::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $balanceHistory = collect();
        }

        // Calculate statistics
        $stats = [
            'total_transactions' => Transaction::where('user_id', $user->id)->count(),
            'successful_transactions' => Transaction::where('user_id', $user->id)->where('status', 'success')->count(),
            'total_spent' => Transaction::where('user_id', $user->id)->where('status', 'success')->sum('amount'),
            'total_topups' => TopupRequest::where('user_id', $user->id)->where('status', 'success')->sum('amount'),
            'referral_count' => $user->referrals()->count(),
        ];

        return Inertia::render('dashboard', [
            'user' => $user,
            'recentTransactions' => $recentTransactions,
            'recentTopups' => $recentTopups,
            'balanceHistory' => $balanceHistory,
            'stats' => $stats,
        ]);
    }
}
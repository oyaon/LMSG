<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistics
        $totalBooks = DB::table('all_books')->count();
        $totalUsers = DB::table('users')->where('user_type', 1)->count();
        $activeBorrows = DB::table('borrow_history')->where('status', 'Issued')->whereNull('return_date')->count();

        // Recent Activities
        $recentActivities = DB::table('borrow_history as bh')
            ->join('all_books as b', 'bh.book_id', '=', 'b.id')
            ->join('users as u', 'bh.user_email', '=', 'u.email')
            ->select('b.name as title', 'u.user_name as username', 'bh.issue_date as borrow_date')
            ->orderByDesc('bh.issue_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'activeBorrows', 'recentActivities'));
    }
}
composer updatecopy temp-laravel\app\Http\Kernel.php LMS\lms-laravel\app\Http\Kernel.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\Member;
use App\Models\Asset\AssetItem;
use App\Models\Program\Program;
use App\Models\Program\Activity;
use App\Models\Program\Period;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            // HR Statistics
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'active')->count(),
            'am_count' => Member::where('member_type', 'AM')->count(),
            'at_count' => Member::where('member_type', 'AT')->count(),
            
            // Asset Statistics
            'total_assets' => AssetItem::count(),
            'available_assets' => AssetItem::where('status', 'available')->count(),
            'borrowed_assets' => AssetItem::where('status', 'borrowed')->count(),
            'maintenance_assets' => AssetItem::where('status', 'maintenance')->count(),
            
            // Program Statistics
            'total_programs' => Program::count(),
            'active_programs' => Program::where('status', 'ongoing')->count(),
            'terprogram_count' => Program::where('type', 'terprogram')->count(),
            'insidentil_count' => Program::where('type', 'insidentil')->count(),
            
            // Activity Statistics
            'total_activities' => Activity::count(),
            'upcoming_activities' => Activity::where('status', 'published')
                ->where('start_date', '>=', Carbon::now()->toDateString())
                ->count(),
            'ongoing_activities' => Activity::where('status', 'ongoing')->count(),
            'completed_activities' => Activity::where('status', 'completed')->count(),
        ];

        // Get active period
        $currentPeriod = Period::where('is_active', true)->first();

        // Get upcoming activities (next 7 days)
        $upcomingActivities = Activity::where('status', 'published')
            ->where('start_date', '>=', Carbon::now()->toDateString())
            ->where('start_date', '<=', Carbon::now()->addDays(7)->toDateString())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get recent members
        $recentMembers = Member::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'currentPeriod' => $currentPeriod,
            'upcomingActivities' => $upcomingActivities,
            'recentMembers' => $recentMembers,
        ]);
    }
}

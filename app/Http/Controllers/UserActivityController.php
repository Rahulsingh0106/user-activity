<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivity::query();
        $searchedUser = null;

        if ($request->filled('user_id')) {
            $searchedUser = UserActivity::find($request->user_id);
        }

        $this->applySorting($query, $request->input('sort_by'));

        if ($searchedUser) {
            $query->where('id', '!=', $searchedUser->id);
        }

        $userActivities = $query->get()->sortBy('rank');

        if ($searchedUser) {
            $userActivities->prepend($searchedUser);
        }

        return view('user_activities.index', compact('userActivities'));
    }

    private function applySorting($query, $sortBy)
    {
        switch ($sortBy) {
            case 'day':
                $query->whereDate('activity_date', Carbon::today());
                break;
            case 'month':
                $query->whereMonth('activity_date', Carbon::now()->month)
                    ->whereYear('activity_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('activity_date', Carbon::now()->year);
                break;
        }
    }

    public function recalculateRanks()
    {
        $users = UserActivity::orderByDesc('points')->get();
        $rank = 1;
        $lastPoints = null;

        foreach ($users as $index => $user) {
            if ($lastPoints !== $user->points) {
                $rank = $index + 1;
            }
            $user->rank = $rank;
            $lastPoints = $user->points;
            $user->save();
        }

        return redirect()->route('/')->with('success', 'Leaderboard recalculated successfully!');
    }
}

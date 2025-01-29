<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivity::query();
        $searchedUser = null; // To store the searched user

        // Check if user_id is provided for search
        if ($request->filled('user_id')) {
            $searchedUser = UserActivity::where('id', $request->user_id)->first();
        }

        // Sorting logic using switch case
        // switch ($request->sort_by) {
        //     case 'day':
        //         $query->whereDate('activity_date', today());
        //         break;
        //     case 'month':
        //         $query->whereMonth('activity_date', now()->month)
        //             ->whereYear('activity_date', now()->year);
        //         break;
        //     case 'year':
        //         $query->whereYear('activity_date', now()->year);
        //         break;
        // }

        // Exclude searched user from the main query
        if ($searchedUser) {
            $query->where('id', '!=', $searchedUser->id);
        }

        // Get the remaining users
        $userActivities = $query->get()->sortBy('rank');

        // Prepend searched user to the results
        if ($searchedUser) {
            $userActivities->prepend($searchedUser);
        }

        return view('user_activities.index', compact('userActivities'));
    }

    public function recalculateRanks()
    {
        // Fetch all users sorted by points in descending order
        $users = UserActivity::orderByDesc('points')->get();

        // Assign ranks dynamically (same points = same rank)
        $rank = 1;
        $lastPoints = null;
        foreach ($users as $index => $user) {
            if ($lastPoints !== $user->points) {
                $rank = $index + 1;
            }
            $user->rank = $rank;
            $lastPoints = $user->points;
            $user->save(); // Save updated rank
        }

        return redirect()->route('/')->with('success', 'Leaderboard recalculated successfully!');
    }
}

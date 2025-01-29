<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserActivitySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = [];

        // Generate users with random points (some having the same points)
        foreach (range(1, 50) as $index) {
            $points = [100, 200, 300, 400, 500][array_rand([100, 200, 300, 400, 500])]; // Random fixed points
            $activityDate = match (rand(1, 3)) {
                1 => Carbon::today(),
                2 => Carbon::now()->startOfMonth()->addDays(rand(0, now()->day - 1)),
                3 => Carbon::now()->subMonths(rand(1, 6))->startOfMonth()->addDays(rand(0, 27)),
            };

            $users[] = [
                'id' => $index,
                'full_name' => $faker->name,
                'activity_date' => $activityDate->format('Y-m-d H:i:s'),
                'points' => $points,
            ];
        }

        // Sort users by points in descending order (higher points first)
        usort($users, fn($a, $b) => $b['points'] <=> $a['points']);

        // Assign ranks (same points = same rank)
        $rank = 1;
        $lastPoints = null;
        foreach ($users as &$user) {
            if ($lastPoints !== $user['points']) {
                $rank = count(array_filter($users, fn($u) => $u['points'] > $user['points'])) + 1;
            }
            $user['rank'] = $rank;
            $lastPoints = $user['points'];
        }

        // Insert users into the database
        DB::table('user_activities')->insert($users);
    }
}

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PingsCount
{
    /**
     * Get number of hosts and number of contacts.
     *
     * @param  Carbon  $date_min
     * @param  Carbon  $date_max
     * @return array
     */
    public function getCounts(Carbon $date_min, Carbon $date_max): array
    {
        $pings = DB::table('pings')
            ->from(function ($query) use ($date_min, $date_max) {
                return $query
                    ->select('host_id', DB::raw('MAX(number_of_contacts) as number'))
                    ->from('pings')
                    ->where('created_at', '>=', $date_min->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date_max->format('Y-m-d 00:00:00'))
                    ->groupBy('host_id');
            }, 't');
        $count = $pings->count();
        $sum = $pings->sum('t.number');

        return [$count, $sum];
    }

    /**
     * Get number of new hosts.
     *
     * @param  Carbon  $date_min
     * @param  Carbon  $date_max
     * @return int
     */
    public function getNews(Carbon $date_min, Carbon $date_max): int
    {
        return DB::table('pings')
            ->from(function ($query) use ($date_min, $date_max) {
                return $query
                    ->select('host_id')
                    ->from('pings')
                    ->where('created_at', '>=', $date_min->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date_max->format('Y-m-d 00:00:00'))
                    ->whereNotIn('host_id', function ($query) use ($date_min) {
                        return $query
                            ->select('host_id')
                            ->from('pings')
                            ->where('created_at', '<', $date_min->format('Y-m-d 00:00:00'))
                            ->groupBy('host_id')
                            ->get();
                    })
                    ->groupBy('host_id');
            })
            ->count();
    }

    /**
     * Get number of stale hosts.
     *
     * @param  Carbon  $date_min
     * @param  Carbon  $date_max
     * @return int|null
     */
    public function getStales(Carbon $date_min, Carbon $date_max): ?int
    {
        return $date_max < now() ? DB::table('pings')
            ->from(function ($query) use ($date_min, $date_max) {
                return $query
                    ->select('host_id')
                    ->from('pings')
                    ->where('created_at', '>=', $date_min->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date_max->format('Y-m-d 00:00:00'))
                    ->whereNotIn('host_id', function ($query) use ($date_max) {
                        return $query
                            ->select('host_id')
                            ->from('pings')
                            ->where('created_at', '>=', $date_max->format('Y-m-d 00:00:00'))
                            ->groupBy('host_id')
                            ->get();
                    })
                    ->groupBy('host_id');
            })
            ->count() : null;
    }
}

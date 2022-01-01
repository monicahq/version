<?php

namespace App\Services\Aggregate;

use App\Models\AggregateContactsWeek;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AggregateWeek extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @param  array  $data
     * @return array
     */
    public function rules(array $data)
    {
        return [
            'date' => 'date|required',
        ];
    }

    /**
     * Aggregate week.
     *
     * @param  array  $data
     * @return void
     */
    public function execute(array $data): void
    {
        $this->validate($data);

        $date = Carbon::parse($data['date'])->startOfWeek();

        $pings = DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('host_id', DB::raw('MAX(number_of_contacts) as number'))
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addWeek()->startOfWeek()->format('Y-m-d 00:00:00'))
                    ->groupBy('host_id');
            }, 't');
        $count = $pings->count();
        $sum = $pings->sum('t.number');

        $new = DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('host_id')
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addWeek()->startOfWeek()->format('Y-m-d 00:00:00'))
                    ->whereNotIn('host_id', function ($query) use ($date) {
                        return $query
                            ->select('host_id')
                            ->from('pings')
                            ->where('created_at', '<', $date->format('Y-m-d 00:00:00'))
                            ->groupBy('host_id')
                            ->get();
                    })
                    ->groupBy('host_id');
            })
            ->count();
        $stale = $date->copy()->addWeek() < now() ? DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('host_id')
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addWeek()->startOfWeek()->format('Y-m-d 00:00:00'))
                    ->whereNotIn('host_id', function ($query) use ($date) {
                        return $query
                            ->select('host_id')
                            ->from('pings')
                            ->where('created_at', '>=', $date->copy()->addWeek()->startOfWeek()->format('Y-m-d 00:00:00'))
                            ->groupBy('host_id')
                            ->get();
                    })
                    ->groupBy('host_id');
            })
            ->count() : null;

        if ($count > 0) {
            $week = AggregateContactsWeek::firstOrCreate(
                ['date' => $date],
            );
            $week->count = $count;
            $week->new = $new;
            $week->stale = $stale;
            $week->number_of_contacts = $sum;
            $week->save();
        }
    }
}

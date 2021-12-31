<?php

namespace App\Services\Aggregate;

use App\Models\AggregateContactsDay;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AggregateDay extends BaseService
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
     * Aggregate day.
     *
     * @param  array  $data
     * @return void
     */
    public function execute(array $data): void
    {
        $this->validate($data);

        $date = Carbon::parse($data['date'])->startOfDay();

        $pings = DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('host_id', DB::raw('MAX(number_of_contacts) as number'))
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addDay()->format('Y-m-d 00:00:00'))
                    ->groupBy('host_id');
            }, 't');
        $count = $pings->count();
        $sum = $pings->sum('t.number');

        if ($count > 0) {
            $day = AggregateContactsDay::firstOrCreate(
                ['date' => $date],
            );
            $day->count = $count;
            $day->number_of_contacts = $sum;
            $day->save();
        }
    }
}

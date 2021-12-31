<?php

namespace App\Services\Aggregate;

use App\Models\AggregateContactsMonth;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AggregateMonth extends BaseService
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
     * Aggregate month.
     *
     * @param  array  $data
     * @return void
     */
    public function execute(array $data): void
    {
        $this->validate($data);

        $date = Carbon::parse($data['date'])->startOfMonth();

        $count = DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('uuid')
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addMonth()->startOfMonth()->format('Y-m-d 00:00:00'))
                    ->groupBy('uuid');
            })
            ->count();

        $sum = DB::table('pings')
            ->from(function ($query) use ($date) {
                return $query
                    ->select('uuid', DB::raw('MAX(number_of_contacts) as number'))
                    ->from('pings')
                    ->where('created_at', '>=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at', '<', $date->copy()->addMonth()->startOfMonth()->format('Y-m-d 00:00:00'))
                    ->groupBy('uuid');
            }, 't')
            ->sum('t.number');

        if ($count > 0) {
            $week = AggregateContactsMonth::firstOrCreate(
                ['date' => $date],
            );
            $week->count = $count;
            $week->number_of_contacts = $sum;
            $week->save();
        }
    }
}

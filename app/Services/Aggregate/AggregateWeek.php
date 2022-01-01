<?php

namespace App\Services\Aggregate;

use App\Helpers\PingsCount;
use App\Models\AggregateContactsWeek;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

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

        $date_min = Carbon::parse($data['date'])->startOfWeek();
        $date_max = $date_min->copy()->addWeek()->startOfWeek();

        [$count, $sum] = app(PingsCount::class)->getCounts($date_min, $date_max);
        $new = app(PingsCount::class)->getNews($date_min, $date_max);
        $stale = app(PingsCount::class)->getStales($date_min, $date_max);

        if ($count > 0) {
            $week = AggregateContactsWeek::firstOrCreate(
                ['date' => $date_min],
            );
            $week->count = $count;
            $week->new = $new;
            $week->stale = $stale;
            $week->number_of_contacts = $sum;
            $week->save();
        }
    }
}

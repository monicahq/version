<?php

namespace App\Services\Aggregate;

use App\Helpers\PingsCount;
use App\Models\AggregateContactsMonth;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

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

        $date_min = Carbon::parse($data['date'])->startOfMonth();
        $date_max = $date_min->copy()->addMonth()->startOfMonth();

        [$count, $sum] = app(PingsCount::class)->getCounts($date_min, $date_max);
        $new = app(PingsCount::class)->getNews($date_min, $date_max);
        $stale = app(PingsCount::class)->getStales($date_min, $date_max);

        if ($count > 0) {
            $week = AggregateContactsMonth::firstOrCreate(
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

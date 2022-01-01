<?php

namespace App\Services\Aggregate;

use App\Helpers\PingsCount;
use App\Models\AggregateContactsDay;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

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

        $date_min = Carbon::parse($data['date'])->startOfDay();
        $date_max = $date_min->copy()->addDay();

        [$count, $sum] = app(PingsCount::class)->getCounts($date_min, $date_max);

        if ($count > 0) {
            $day = AggregateContactsDay::firstOrCreate(
                ['date' => $date_min],
            );
            $day->count = $count;
            $day->number_of_contacts = $sum;
            $day->save();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AggregateContactsDay;
use App\Models\AggregateContactsMonth;
use App\Models\AggregateContactsWeek;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Jetstream;

class ChartsController extends Controller
{
    /**
     * Show releases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Charts', [
            'days' => AggregateContactsDay::all()->map(fn (AggregateContactsDay $item): array => [
                'date' => $item->date->getTimestampMs(),
                'count' => $item->count,
                'number_of_contacts' => $item->number_of_contacts,
            ]),
            'weeks' => AggregateContactsWeek::all()->map(fn (AggregateContactsWeek $item): array => [
                'date' => $item->date->getTimestampMs(),
                'count' => $item->count,
                'new' => $item->new,
                'stale' => $item->stale,
                'number_of_contacts' => $item->number_of_contacts,
            ]),
            'months' => AggregateContactsMonth::all()->map(fn (AggregateContactsMonth $item): array => [
                'date' => $item->date->getTimestampMs(),
                'count' => $item->count,
                'new' => $item->new,
                'stale' => $item->stale,
                'number_of_contacts' => $item->number_of_contacts,
            ]),
        ]);
    }
}

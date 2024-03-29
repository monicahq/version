<?php

namespace App\Services\Ping;

use App\Models\Host;
use App\Models\Ping;
use App\Services\BaseService;

class CreatePing extends BaseService
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
            'uuid' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'contacts' => 'required|integer',
        ];
    }

    /**
     * Create a ping.
     *
     * @param  array  $data
     * @return void
     */
    public function execute(array $data): void
    {
        $this->validate($data);

        $host = Host::firstOrCreate(['uuid' => $data['uuid']]);

        Ping::create([
            'host_id' => $host->id,
            'version' => $data['version'],
            'number_of_contacts' => $data['contacts'],
        ]);
    }
}

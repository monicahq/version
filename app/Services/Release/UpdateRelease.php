<?php

namespace App\Services\Release;

use App\Models\Release;
use App\Services\BaseService;

class UpdateRelease extends BaseService
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
            'release_id' => 'required|integer|exists:releases,id',
            'version' => 'required|string|max:255',
            'notes' => 'required|string|max:16777215',
            'released_on' => 'required|date',
        ];
    }

    /**
     * Update a release.
     *
     * @param  array  $data
     * @return Release
     */
    public function execute(array $data): Release
    {
        $this->validate($data);

        $release = Release::findOrFail($data['release_id']);

        $release->update([
            'version' => $this->nullOrValue($data, 'version'),
            'notes' => $this->nullOrValue($data, 'notes'),
            'released_on' => $this->nullOrDate($data, 'released_on'),
        ]);

        $release->refresh();

        return $release;
    }
}

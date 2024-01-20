<?php

namespace Modules\Employees\Jobs\Department;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use Modules\Employees\Models\Department;

class UpdateDepartment extends Job implements ShouldUpdate
{
    public function handle(): Department
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }

    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'employees' => 'employees::general.employees',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}

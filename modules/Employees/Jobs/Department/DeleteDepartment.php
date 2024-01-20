<?php

namespace Modules\Employees\Jobs\Department;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteDepartment extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        $this->model->delete();

        return true;
    }

    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

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

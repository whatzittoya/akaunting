<?php

namespace Modules\Employees\Jobs\Department;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use Modules\Employees\Models\Department;

class CreateDepartment extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Department
    {
        \DB::transaction(function () {
            $this->model = Department::create($this->request->all());
        });

        return $this->model;
    }
}

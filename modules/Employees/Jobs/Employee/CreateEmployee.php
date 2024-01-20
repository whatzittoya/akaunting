<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use Modules\Employees\Events\EmployeeCreated;
use Modules\Employees\Events\EmployeeCreating;
use Modules\Employees\Models\Employee;

class CreateEmployee extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Employee
    {
        event(new EmployeeCreating($this->request));

        \DB::transaction(function () {
            if (!strstr($this->request->created_from, 'employees')) {
                $this->request->merge(['created_from' => source_name('employees')]);
            };

            $contact = $this->dispatch(new CreateEmployeeContact($this->request));

            $this->request->merge(['contact_id' => $contact->id]);

            $this->model = Employee::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'employees');

                    $this->model->attachMedia($media, 'attachment');
                }
            }
        });

        event(new EmployeeCreated($this->model, $this->request));

        return $this->model;
    }
}

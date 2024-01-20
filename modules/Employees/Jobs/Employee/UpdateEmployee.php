<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use Modules\Employees\Events\EmployeeUpdated;
use Modules\Employees\Events\EmployeeUpdating;
use Modules\Employees\Models\Employee;

class UpdateEmployee extends Job implements ShouldUpdate
{
    public function handle(): Employee
    {
        event(new EmployeeUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->dispatch(new UpdateEmployeeContact($this->model->contact, $this->request));
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'employees');

                    $this->model->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            $this->model->update($this->request->all());

        });

        event(new EmployeeUpdated($this->model, $this->request));

        return $this->model;
    }
}

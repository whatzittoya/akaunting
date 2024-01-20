<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;
use Modules\Employees\Models\Employee;

class EmployeeDuplicated extends Event
{
    public $employee;

    public $duplicated_employee;

    public function __construct(Employee $employee, Employee $duplicated_employee)
    {
        $this->employee = $employee;
        $this->duplicated_employee = $duplicated_employee;
    }
}

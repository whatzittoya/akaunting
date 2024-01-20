<?php

namespace Modules\Employees\Imports;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as ContactRequest;
use App\Models\Common\Contact;
use Modules\Employees\Http\Requests\Employee as EmployeeRequest;
use Modules\Employees\Models\Employee as Model;
use Modules\Employees\Models\Department;

class Employees extends Import
{
    public function batchSize(): int
    {
        return 1;
    }

    public function model(array $row): ?Model
    {
        // TODO: use `implements OnEachRow` when Laravel Excel will use WithMapping with OnEachRow

        if ($this->isEmpty($row, 'department_id')) {
            $department = Department::create([
                'company_id' => $row['company_id'],
                'name'       => $row['department'],
                'enabled'    => true,
            ]);
            $row['department_id'] = $department->id;
        }

        $contact = Contact::create($row);
        $row['contact_id'] = $contact->id;

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'employee';
        $row['department_id'] = (int) Department::where('name', $row['department'])->pluck('id')->first();
        $row['amount'] = $row['salary'];

        return $row;
    }

    public function rules(): array
    {
        $employee_rules = array_filter((new EmployeeRequest())->rules(), function ($value, $key) {
            return in_array($key, [
                'birth_day',
                'gender',
                'department_id',
                'amount',
                'hired_at',
                'currency_code',
            ]);
        }, ARRAY_FILTER_USE_BOTH);

        $rules = array_merge(
            (new ContactRequest([], ['email' => 'just a string to trigger adding the email rule']))->rules(),
            $employee_rules
        );

        return $this->replaceForBatchRules($rules);
    }
}

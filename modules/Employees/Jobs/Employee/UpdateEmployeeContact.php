<?php

namespace Modules\Employees\Jobs\Employee;

use App\Jobs\Common\UpdateContact;
use App\Models\Auth\Role;
use App\Jobs\Auth\CreateUser;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Modules\Employees\Jobs\CreateEmployeeDashboard;

class UpdateEmployeeContact extends UpdateContact
{
    public function createUser(): void
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $employee_role_id = setting('employees.default_role_id') ?? Role::where('name', 'employee')->first()?->id;

        if (! $employee_role_id) {
            $message = trans('employees::employees.messages.role_missing');

            throw new \Exception($message);
        }

        $this->request->merge([
            'locale' => setting('default.locale', 'en-GB'),
            'roles' => $employee_role_id,
            'companies' => [$this->request->get('company_id')],
        ]);

        $user = $this->dispatch(new CreateUser($this->request));

        $this->dispatch(new CreateEmployeeDashboard($user->id));

        $this->request['user_id'] = $user->id;
    }

    public function countRelationships($model, $relationships): array
    {
        $record = new \stdClass();
        $record->model = $model;
        $record->relationships = $relationships;

        $counter = [];

        foreach ((array)$record->relationships as $relationship => $text) {
            if (!$c = $model->$relationship()->count()) {
                continue;
            }

            $text = Str::contains($text, '::') ? $text : 'general.' . $text;
            $counter[] = $c . ' ' . strtolower(trans_choice($text, ($c > 1) ? 2 : 1));
        }

        return $counter;
    }
}

<?php

namespace Modules\Employees\Jobs\Employee;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateContact;
use Modules\Employees\Jobs\CreateEmployeeDashboard;

class CreateEmployeeContact extends CreateContact
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
}

<?php

use Modules\Employees\Models\Department;
use Modules\Employees\Models\Employee;

return [
    Employee::class => [
        [
            'location' => [
                'code' => 'employees-employees',
                'name' => 'employees::general.name',
            ],
            'sort_orders' => [
                'name'                  => 'general.name',
                'email'                 => 'general.email',
                'birth_day'             => 'employees::employees.birth_day',
                'gender'                => 'employees::employees.gender',
                'phone'                 => 'general.phone',
                'department_id'         => ['employees::general.departments', 1],
                'create_user'           => 'customers.can_login',
                'address'               => 'general.address',
                'city'                  => ['general.cities', 1],
                'zip_code'              => 'general.zip_code',
                'state'                 => 'general.state',
                'country'               => ['general.countries', 1],
                'amount'                => 'general.amount',
                'currency_code'         => ['general.currencies', 1],
                'tax_number'            => 'general.tax_number',
                'bank_account_number'   => 'employees::employees.bank_account_number',
                'hired_at'              => 'employees::employees.hired_at',
                'attachment'            => 'general.attachment',
            ],
            'views' => [
                'crud' => [
                    'employees::employees.create',
                    'employees::employees.edit'
                ],
                'show' => [
                    'employees::employees.show'
                ],
            ],
        ],
        'export' => 'Modules\Employees\Exports\Employees',
    ],
    Department::class => [
        [
            'location' => [
                'code' => 'employees-settings',
                'name' => 'employees::general.departments',
            ],
            'sort_orders' => [
                'name'          => 'general.name',
                'manager_id'    => 'employees::general.manager',
                'parent_id'     => 'employees::general.parent_department',
                'description'   => 'general.description',
            ],
            'views' => [
                'crud' => [
                    'employees::settings.departments.create',
                    'employees::settings.departments.edit'
                ],
                'show' => [

                ],
            ],
        ],
        'export' => 'Modules\Employees\Exports\Departments',
    ],
];

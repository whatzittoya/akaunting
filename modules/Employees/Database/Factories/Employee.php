<?php

namespace Modules\Employees\Database\Factories;

use App\Abstracts\Factory as AbstractFactory;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee as Model;
use Modules\Employees\Models\Department;

class Employee extends AbstractFactory
{
    /**
     * 
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public function definition(): array
    {
        $contact_request = Contact::factory()->enabled()->raw();
        $contact_request['type'] = 'employee';
        
        $department_id = Department::enabled()->inRandomOrder()->pluck('id')->first();
        if (!$department_id) {
            $department_id = Department::factory()->enabled()->create()->id;
        }

        $date = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
        return array_merge($contact_request, [
            'birth_day'             => $date,
            'hired_at'              => $date,
            'amount'                => $this->faker->randomFloat(2, 10, 20),
            'salary_type'           => 'monthly',
            'department_id'         => $department_id,
            'gender'                => $this->faker->randomElement(Model::getAvailableGenders()),
            'bank_account_number'   => $this->faker->iban(),
        ]);
    }
}

<?php

namespace Modules\Employees\Widgets;

use App\Abstracts\Widget;
use Modules\Employees\Models\Employee;

class EmployeeGender extends Widget
{
    public $default_name = 'employees::widgets.employee_gender';

    public $description = 'employees::widgets.description.employee_gender';

    public function show()
    {
        $employees = Employee::enabled()->whereNotNull('gender')->get();

        $genders = [
            'male' => 0,
            'female' => 0,
            'other' => 0,
        ];

        foreach ($employees as $employee) {
            switch ($employee->gender) {
                case 'male':
                    $genders['male']++;
                    break;
                case 'female':
                    $genders['female']++;
                    break;
                case 'other':
                    $genders['other']++;
                    break;
            }
        }

        if (array_sum($genders) == 0) {
            return;
        }

        foreach ($genders as $key => $gender) {
            if ($gender == 0) {
                continue;
            }

            $rand = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b'];
            $color = '#' . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)];

            $label = trans('employees::widgets.genders.' . $key);

            $this->addToDonut($color, $label, $gender);
        }

        $chart = $this->getDonutChart(trans('employees::widgets.employee_gender'), '100%', 300, 6);

        $chart->options['legend']['width'] = 160;
        $chart->options['legend']['position'] = 'right';

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}

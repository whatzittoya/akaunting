<?php

namespace Modules\Employees\Widgets;

use App\Abstracts\Widget;
use Date;
use Modules\Employees\Models\Employee;

class EmployeeAge extends Widget
{
    public $default_name = 'employees::widgets.employee_age';

    public $description = 'employees::widgets.description.employee_age';

    public function show()
    {
        $employees = Employee::enabled()->whereNotNull('birth_day')->get();

        $age_ranges = [
            '20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51' => 0,
        ];

        foreach ($employees as $employee) {

            $birth_year = Date::parse($employee->birth_day)->format('Y');
            $now_year = Date::now()->format('Y');
            $age = $now_year - $birth_year;

            if ($age <= 20) {
                $age_ranges['20']++;
            } elseif ($age >= 21 && $age <= 30) {
                $age_ranges['21-30']++;
            } elseif ($age >= 31 && $age <= 40) {
                $age_ranges['31-40']++;
            } elseif ($age >= 41 && $age <= 50) {
                $age_ranges['41-50']++;
            } elseif ($age >= 51) {
                $age_ranges['51']++;
            }
        }

        if (array_sum($age_ranges) == 0) {
            return;
        }

        foreach ($age_ranges as $key => $age_range) {
            if ($age_range == 0) {
                continue;
            }

            $rand = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b'];
            $color = '#' . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)] . $rand[rand(0, 11)];

            $label = trans('employees::widgets.age_range.' . $key);

            $this->addToDonut($color, $label, $age_range);
        }

        $chart = $this->getDonutChart(trans('employees::widgets.employee_age'), '100%', 300, 6);

        $chart->options['legend']['width'] = 160;
        $chart->options['legend']['position'] = 'right';

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}

<?php

namespace Modules\Employees\Widgets;

use Akaunting\Apexcharts\Chart;
use App\Abstracts\Widget;
use App\Utilities\Date;
use App\Traits\Charts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Modules\Employees\Models\Employee;

class NumberOfEmployees extends Widget
{
    use Charts, Currencies, DateTime;

    public $default_name = 'employees::widgets.number_of_employees';

    public $description = 'employees::widgets.description.number_of_employees';

    public $start_date;

    public $end_date;

    public $period;

    public $default_settings = [
        'width' => 'w-full my-8 px-12',
    ];

    public function show()
    {
        $this->setFilter();

        $employees = $this->calculateTotals();

        $totals = array_sum($employees);

        if ($totals != 0) {
            $date_now = Date::now()->format('Y-m');

            foreach ($employees as $key => $employee) {
                $date = Date::parse($key)->addMonths(-1)->format('Y-m');
    
                if ($date == $date_now) {
                    break;
                }
    
                switch ($employee) {
                    case 0:
                        isset($employees[$date]) ? $employees[$key] = $employees[$date] : '';
                        break;
                    
                    default:
                        $employees[$key] = isset($employees[$date]) ? ($employees[$date] + $employee) : $employee;
                        break;
                }
            }
    
        }

        $chart = new Chart();

        $chart->setType('line')
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setStacked(true)
            ->setBar(['columnWidth' => '40%'])
            ->setLegendPosition('top')
            ->setYaxisLabels(['formatter' => $this->getChartLabelFormatter('integer')])
            ->setLabels(array_values($this->getLabels()))
            ->setColors($this->getColors())
            ->setDataset(trans('employees::general.name'), 'column', array_values($employees));

        return $this->view('employees::widgets.number_of_employees', [
            'chart' => $chart,
            'totals' => $totals,
        ]);
    }

    public function setFilter(): void
    {
        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $this->start_date = Date::parse(request('start_date', $year_start));
        $this->end_date = Date::parse(request('end_date', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));
        $this->period = request('period', 'month');
    }

    public function getLabels(): array
    {
        $range = request('range', 'custom');

        $start_month = $this->start_date->month;
        $end_month = $this->end_date->month;

        // Monthly
        $labels = [];

        $s = clone $this->start_date;

        if ($range == 'last_12_months') {
            $end_month   = 12;
            $start_month = 0;
        } elseif ($range == 'custom') {
            $end_month   = $this->end_date->diffInMonths($this->start_date);
            $start_month = 0;
        }

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            if ($this->period == 'month') {
                $s->addMonth();
            } else {
                $s->addMonths(3);
                $j -= 2;
            }
        }

        return $labels;
    }

    public function getColors(): array
    {
        return [
            '#8bb475',
        ];
    }

    private function calculateTotals(): array
    {
        $totals = [];

        $date_format = 'Y-m';

        if ($this->period == 'month') {
            $n = 1;
            $start_date = $this->start_date->format($date_format);
            $end_date = $this->end_date->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $this->start_date->quarter;
            $end_date = $this->end_date->quarter;
            $next_date = $start_date;
        }

        $s = clone $this->start_date;

        //$totals[$start_date] = 0;
        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($this->period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items = $this->applyFilters(Employee::orderBy('hired_at')->whereBetween('hired_at', [$this->start_date, $this->end_date]), ['date_field' => 'hired_at'])->get();

        $this->setTotals($totals, $items, $date_format);

        return $totals;
    }

    private function setTotals(&$totals, $items, $date_format): void
    {
        foreach ($items as $item) {
            if ($this->period == 'month') {
                $i = Date::parse($item->hired_at)->format($date_format);
            } else {
                $i = Date::parse($item->hired_at)->quarter;
            }

            if (! isset($totals[$i])) {
                continue;
            }

            $totals[$i] += 1;
        }
    }
}

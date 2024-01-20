<?php

namespace Modules\Employees\Listeners;

use Date;
use App\Traits\Modules;
use Modules\Employees\Models\Employee;
use Modules\Calendar\Events\CalendarEventCreated as Event;

class AddToEventInCalendar
{
    use Modules;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (! $this->moduleIsEnabled('employees') || ! $this->moduleIsEnabled('calendar')) {
            return;
        }

        if (user()->canAny('read-employees-employees')) {
            $employees = Employee::with('contact')->collect();

            foreach ($employees as $item) {
                if ($item->birth_day) {
                    $event->calendar->events[] = [
                        'title' => trans('employees::employees.calendar.birth_day', ['name' => $item->contact->name]),
                        'start' => Date::parse($item->birth_day)->format('Y-m-d'),
                        'end' => Date::parse($item->birth_day)->addDay(1)->format('Y-m-d'),
                        'type' => 'employees',
                        'id' => $item->id,
                        'backgroundColor' => '#FA26A0',
                        'borderColor' => '#FA26A0',
                        'extendedProps' => [
                            'show' => route('employees.employees.show', $item->id),
                            'edit' => route('employees.employees.edit', $item->id),
                            'description' => trans('employees::employees.calendar.birth_day_description', ['name' => $item->contact->name]) . trans('calendar::general.event_description', ['url' => route('employees.employees.show', $item->id), 'name' => $item->contact->name]),
                            'date' => Date::parse($item->due_at)->format('Y-m-d')
                        ],
                    ];
                }

                $event->calendar->events[] = [
                    'title' => trans('employees::employees.calendar.hired_at', ['name' => $item->contact->name]),
                    'start' => Date::parse($item->hired_at)->format('Y-m-d'),
                    'end' => Date::parse($item->hired_at)->addDay(1)->format('Y-m-d'),
                    'type' => 'employees',
                    'id' => $item->id,
                    'backgroundColor' => '#4C5270',
                    'borderColor' => '#4C5270',
                    'extendedProps' => [
                        'show' => route('employees.employees.show', $item->id),
                        'edit' => route('employees.employees.edit', $item->id),
                        'description' => trans('employees::employees.calendar.hired_at_description', ['name' => $item->contact->name]) . trans('calendar::general.event_description', ['url' => route('employees.employees.show', $item->id), 'name' => $item->contact->name]),
                        'date' => Date::parse($item->due_at)->format('Y-m-d')
                    ],
                ];

            }
        }
    }
}
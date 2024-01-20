<?php

namespace Modules\Employees\Listeners;

use App\Events\Menu\AdminCreated;
use Modules\Employees\Events\AddingHRDropdown;
use Modules\Employees\Events\HRDropdownCreated;

class AddToAdminMenu
{
    /**
     * Handle the event.
     *
     * @param AdminCreated $event
     * @return void
     */
    public function handle(AdminCreated $event)
    {
        $user = user();

        $can_read_employees = $user->can('read-employees-employees');
      //  $can_read_positions = $user->can('read-employees-positions');
        $show_dropdown = $can_read_employees;

        if (! $show_dropdown) {
            event(new AddingHRDropdown($show_dropdown));
        }

        if (! $show_dropdown) {
            return;
        }

        $event->menu->dropdown(trans('employees::general.hr'), function ($sub) use ($can_read_employees) {
            if ($can_read_employees) {
                $sub->route('employees.employees.index', trans_choice('employees::general.employees', 2), [], 10, []);
            }

            event(new HRDropdownCreated($sub));
        }, 41, ['icon' => 'groups']);
    }
}

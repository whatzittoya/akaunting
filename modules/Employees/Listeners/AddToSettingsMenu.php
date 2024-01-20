<?php

namespace Modules\Employees\Listeners;

use App\Events\Menu\SettingsCreated as Event;
use App\Traits\Modules;
use App\Traits\Permissions;

class AddToSettingsMenu
{
    use Modules, Permissions;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (! $this->moduleIsEnabled('employees')) {
            return;
        }

        $title = trans('employees::general.name');

        if ($this->canAccessMenuItem($title, 'read-employees-settings')) {
            $event->menu->route('employees.settings.edit', $title, [], 251, ['icon' => 'badge', 'search_keywords' => trans('employees::general.description')]);
        }
    }
}

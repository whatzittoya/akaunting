<?php

namespace Modules\Employees\Listeners;

use Throwable;
use App\Traits\Jobs;
use App\Events\Module\Uninstalled as Event;
use App\Jobs\Common\DeleteDashboard;
use App\Models\Common\Dashboard;

class FinishUninstallation
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'employees') {
            return;
        }

        $this->deleteDashboard();
    }

    /**
     * Delete dashboard.
     *
     */
    protected function deleteDashboard()
    {
        Dashboard::where('name', trans('employees::general.hr'))
            ->get()
            ->each(function ($dashboard) {
                try {
                    $this->dispatch(new DeleteDashboard($dashboard));
                } catch (Throwable $e) {
                    report($e);
                }
            });
    }
}

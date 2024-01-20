<?php

namespace Modules\DoubleEntry\Listeners;

use App\Events\Module\Uninstalled as Event;
use App\Exceptions\Common\LastDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\DeleteReport;
use App\Models\Common\Dashboard;
use App\Models\Common\Report;
use App\Traits\Jobs;
use Throwable;

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
        if ($event->alias != 'double-entry') {
            return;
        }

        $this->deleteReports($event->alias);
    }

    /**
     * Delete reports.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteReports($alias)
    {
        // For module specific reports
        Report::alias($alias)
            ->get()
            ->each(function ($report) {
                try {
                    $this->dispatch(new DeleteReport($report));
                } catch (Throwable $e) {
                    report($e);
                }
            });

        // For chart of account based reports
        Report::where('settings', 'like', '%de_account%')
            ->get()
            ->each(function ($report) {
                try {
                    $this->dispatch(new DeleteReport($report));
                } catch (Throwable $e) {
                    report($e);
                }
            });
    }
}

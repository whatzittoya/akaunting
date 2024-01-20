<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\DB;

class Version208 extends Listener
{
    const ALIAS = 'employees';

    const VERSION = '2.0.8';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();
    }

    protected function updateDatabase()
    {
        DB::table('employees_employees')->whereNull('deleted_At')->get()->each(function ($employee) {
            $contact = DB::table('contacts')->where('id', $employee->contact_id)->whereNotNull('deleted_At')->first();

            if ($contact) {
                DB::table('employees_employees')->where('id', $employee->id)->update(['deleted_at' => $contact->deleted_at]);
            }
        });
    }
}

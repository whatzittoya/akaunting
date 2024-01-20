<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;

class Version2010 extends Listener
{
    const ALIAS = 'employees';

    const VERSION = '2.0.10';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);

    }
}

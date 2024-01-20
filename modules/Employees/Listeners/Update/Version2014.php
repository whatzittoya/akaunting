<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version2014 extends Listener
{
    const ALIAS = 'employees';

    const VERSION = '2.0.14';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Log::channel('stdout')->info('Updating to 2.0.14 version...');

        DB::table('settings')->where('key', 'contact.type.vendor')->where('value', 'vendor,employee')->update(['value' => 'vendor']);

        Log::channel('stdout')->info('Done!');
    }
}

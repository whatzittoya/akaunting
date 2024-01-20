<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Utilities\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Version206 extends Listener
{
    const ALIAS = 'employees';

    const VERSION = '2.0.6';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();
        $this->deleteOldFiles();
    }

    protected function updateDatabase()
    {
        $migration = DB::table('migrations')->where('migration', '2022_07_01_081739_employees_v3')->first();

        if (! $migration) {
            Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);
        } else {
            DB::table('migrations')->insert([
                'id' => DB::table('migrations')->max('id') + 1,
                'migration' => '2022_07_01_081739_employees_v2',
                'batch' => DB::table('migrations')->max('batch') + 1,
            ]);

            DB::table('migrations')->where('migration', '2022_07_01_081739_employees_v3')->delete();    
        }

        $classes = [
            'Modules\Employees\Widgets\EmployeeProfile',
            'Modules\Employees\Widgets\Profile',
            'Modules\Employees\Widgets\TotalEmployees',
        ];

        DB::table('widgets')->whereIn('class', $classes)->update([
            'deleted_at' => Date::now()->toDateTimeString()
        ]);
    }

    public function deleteOldFiles(): void
    {
        $files = [
            'Listeners/Update/Version300.php',
            'Database/Migrations/2022_07_01_081739_employees_v3.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/Employees/' . $file));
        }
    }
}

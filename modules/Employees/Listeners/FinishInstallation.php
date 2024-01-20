<?php

namespace Modules\Employees\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Contacts;
use App\Traits\Permissions;
use Artisan;

class FinishInstallation
{
    use Contacts, Permissions;

    public $alias = 'employees';

    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->updatePermissions();
        $this->callSeeds();
    }

    protected function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-employees' => 'c,r,u,d',
            $this->alias . '-departments' => 'c,r,u,d',
            $this->alias . '-settings' => 'c,r,u,d',
        ]);

        $this->attachModuleWidgetPermissions($this->alias);
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Employees\Database\Seeds\EmployeesDatabaseSeeder',
        ]);
    }
}

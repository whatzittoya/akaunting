<?php
namespace Modules\Employees\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Auth\Role;
use Illuminate\Database\Seeder;
use App\Traits\Permissions as Helper;

class Permissions extends Seeder
{
    use Helper;

    public $alias = 'employees';

    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $rows = [
            'employee' => [
                'admin-panel' => 'r',
                'common-dashboards' => 'c,r,u,d',
                'common-items' => 'c,r',
                'common-search' => 'r',
                'purchases-vendors' => 'c,r',
                'settings-taxes' => 'c,r',
                'common-widgets' => 'c,r,u,d',
                $this->alias . '-widgets-employee-profile' => 'r',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);

        $role_id = Role::where('name', 'employee')->value('id');
        
        if ($role_id) {
            setting()->set('employees.default_role_id', $role_id);
            setting()->save();
        }
    }
}

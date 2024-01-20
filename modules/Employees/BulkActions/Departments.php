<?php

namespace Modules\Employees\BulkActions;

use App\Abstracts\BulkAction;
use Modules\Employees\Models\Department;
use Modules\Employees\Jobs\Department\DeleteDepartment;
use Modules\Employees\Jobs\Department\UpdateDepartment;

class Departments extends BulkAction
{
    public $model = Department::class;

    public $text = 'employees::general.departments';

    public $path = [
        'group' => 'employees',
        'type' => 'departments',
    ];

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'path' =>  ['group' => 'employees', 'type' => 'departments'],
            'type' => '*',
            'permission' => 'delete-employees-employees',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteDepartment($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}

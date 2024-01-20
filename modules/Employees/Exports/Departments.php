<?php

namespace Modules\Employees\Exports;

use App\Abstracts\Export;
use Modules\Employees\Models\Department as Model;

class Departments extends Export
{
    public function collection()
    {
        return Model::collectForExport($this->ids);
    }

    public function fields(): array
    {
        return [
            'name',
            'manager',
            'parent_id',
            'description',
            'enabled',
        ];
    }
}

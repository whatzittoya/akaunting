<?php

namespace Modules\Employees\Imports;

use App\Abstracts\Import;
use Modules\Employees\Http\Requests\Department as Request;
use Modules\Employees\Models\Department as Model;

class Departments extends Import
{
    public function model(array $row): Model
    {
        return new Model($row);
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}

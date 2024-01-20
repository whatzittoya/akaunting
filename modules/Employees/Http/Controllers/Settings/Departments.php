<?php

namespace Modules\Employees\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use Illuminate\Http\JsonResponse;
use Modules\Employees\Exports\Departments as Export;
use Modules\Employees\Http\Requests\Department as Request;
use Modules\Employees\Imports\Departments as Import;
use Modules\Employees\Jobs\Department\CreateDepartment;
use Modules\Employees\Jobs\Department\DeleteDepartment;
use Modules\Employees\Jobs\Department\UpdateDepartment;
use Modules\Employees\Models\Department;

class Departments extends Controller
{
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-employees-departments')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-employees-departments')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-employees-departments')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-employees-departments')->only('destroy');
    }

    public function create()
    {
        $departments = Department::enabled()->pluck('name', 'id');

        $managers = company()->users()->pluck('name', 'id')->sortBy('name');

        return view('employees::settings.departments.create', compact('departments', 'managers'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateDepartment($request));

        if ($response['success']) {
            $response['redirect'] = route('employees.settings.edit');

            $message = trans('messages.success.added', ['type' => trans_choice('employees::general.departments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.settings.departments.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function edit(Department $department)
    {        
        $departments = Department::enabled()->where('id', '!=', $department->id)->pluck('name', 'id');
        
        $managers = company()->users()->pluck('name', 'id')->sortBy('name');

        return view('employees::settings.departments.edit', compact('department', 'departments', 'managers'));
    }

    public function update(Department $department, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateDepartment($department, $request));

        if ($response['success']) {
            $response['redirect'] = route('employees.settings.edit');

            $message = trans('messages.success.updated', ['type' => $department->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.settings.departments.edit', $department->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('employees::general.departments', 2));

        if ($response['success']) {
            $response['redirect'] = route('employees.settings.edit');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['employees', 'departments']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    public function destroy(Department $department): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeleteDepartment($department));

        $response['redirect'] = route('employees.settings.edit');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $department->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('employees::general.departments', 2));
    }
}

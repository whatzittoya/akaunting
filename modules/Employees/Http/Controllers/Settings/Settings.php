<?php

namespace Modules\Employees\Http\Controllers\Settings;

use App\Traits\Modules;
use App\Models\Auth\Role;
use App\Abstracts\Http\Controller;
use Modules\Employees\Models\Department;
use Modules\Employees\Http\Requests\Setting as Request;

class Settings extends Controller
{
    use Modules;

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-employees-settings')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-employees-settings')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-employees-settings')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-employees-settings')->only('destroy');
    }

    public function edit()
    {
        $roles = Role::pluck('display_name', 'id');

        if ($this->moduleIsEnabled('roles')) {
            $roles = \Modules\Roles\Models\Role::allWithCore()->pluck('display_name', 'id');
        }

        $departments = Department::with('sub_departments')->collect();

        return view('employees::settings.index', compact('departments', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        foreach ($request->request as $key => $value) {
            if ($key == '_token' || $key == 'company_id' || $key == '_method') {
                continue;
            }

            setting()->set('employees.' . $key, $value);
        }

        setting()->save();

        flash(trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]))->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('employees.settings.edit'),
        ]);
    }
}

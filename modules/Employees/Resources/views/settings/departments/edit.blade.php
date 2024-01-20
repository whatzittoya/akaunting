<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('employees::general.departments', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" :route="['employees.settings.departments.update', $department->id]" :model="$department">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.select name="manager_id" label="{{ trans('employees::general.manager') }}" :options="$managers" :selected="$department->manager_id" not-required />

                        <x-form.group.select name="parent_id" label="{{ trans('employees::general.parent_department') }}" :options="$departments" :selected="$department->parent_id" not-required />
   
                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="employees.settings.edit" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script alias="employees" file="settings" />
</x-layouts.admin>

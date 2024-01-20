<x-index.container>
    <x-form id="setting" method="POST" route="employees.settings.update">
        <x-form.section>
            <x-slot name="head">
                <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('employees::employees.form_description.setting_general') }}" />
            </x-slot>

            <x-slot name="body">
                <x-form.group.select name="default_role_id" label="{{ trans('employees::general.default_role') }}" :options="$roles" :selected="old('default_role_id', setting('employees.default_role_id'))" />

                <x-form.group.select name="default_salary_type" label="{{ trans('employees::general.salary_type') }}" :options="trans('employees::employees.salary_types')" :selected="old('default_salary_type', setting('employees.default_salary_type', 'monthly'))" />
            </x-slot>
        </x-form.section>

        <x-form.section>
            <x-slot name="foot">
                <x-form.buttons :cancel="url()->previous()" />
            </x-slot>
        </x-form.section>
    </x-form>
</x-index.container>

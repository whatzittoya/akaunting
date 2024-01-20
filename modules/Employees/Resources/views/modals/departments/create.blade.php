<x-form id="form-create-department" route="employees.modals.departments.store">
    <x-form.section>
        <x-slot name="body">
            <x-form.group.text name="name" label="{{ trans('general.name') }}" />

            <x-form.group.select name="manager_id" label="{{ trans('employees::general.manager') }}" :options="$managers" form-group-class="sm:col-span-3" not-required />

            <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required/>

            <x-form.input.hidden name="enabled" value=1 />
        </x-slot>
    </x-form.section>
</x-form>

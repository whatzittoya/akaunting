<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('employees::general.employees', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="employee" method="PATCH" :route="['employees.employees.update', $employee->id]" :model="$employee">
                @stack('card_personal_information_start')
                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans('employees::employees.personal_information') }}" description="{{ trans('employees::employees.form_description.personal_information') }}" />
                        </x-slot>

                        <x-slot name="body">
                            <div class="sm:col-span-3 grid gap-x-8 gap-y-6">
                                <x-form.group.text name="name" label="{{ trans('general.name') }}" :value="$employee->contact->name" />

                                <x-form.group.text name="email" label="{{ trans('general.email') }}" :value="$employee->contact->email" not-required />
                            </div>

                            <div class="sm:col-span-3">
                                <x-form.group.file name="logo" label="{{ trans_choice('general.pictures', 1) }}" :value="! empty($employee->contact) ? $employee->contact->logo : false" not-required />
                            </div>

                            <x-form.group.date name="birth_day" label="{{ trans('employees::employees.birth_day') }}" icon="calendar_today" value="{{ Date::parse($employee->birth_day)->toDateString() }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" />

                            <x-form.group.select name="gender" label="{{ trans('employees::employees.gender') }}" :options="$genders" :selected="$employee->gender" form-group-class="sm:col-span-3" />

                            <x-form.group.text name="phone" label="{{ trans('general.phone') }}" :value="$employee->contact->phone" not-required />

                            <x-form.group.select add-new name="department_id" label="{{ trans_choice('employees::general.departments', 1) }}" :options="$departments" :selected="$employee->department_id" :path="route('employees.modals.departments.create')" :field="['key' => 'id', 'value' => 'name']" form-group-class="sm:col-span-3 el-select-tags-pl-38" />

                            @if ($employee->contact->user_id)
                                <x-form.group.checkbox
                                    name="create_user"
                                    :options="['1' => trans('customers.user_created')]"
                                    checkbox-class="sm:col-span-6"
                                    checked
                                    disabled
                                />
                            @else
                                <x-form.group.checkbox
                                    name="create_user"
                                    :options="['1' => trans('employees::employees.can_login')]"
                                    checkbox-class="sm:col-span-6"
                                    @input="onCanLogin($event)"
                                />
                            @endif
                            
                            <x-form.input.hidden name="type" value="employee" />
                        </x-slot>
                    </x-form.section>
                @stack('card_personal_information_end')

                @stack('card_address_start')
                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans('general.address') }}" />
                        </x-slot>

                        <x-slot name="body">
                            <x-form.group.textarea name="address" label="{{ trans('general.address') }}" :value="$employee->contact->address" v-model='form.address' not-required/>
                            
                            <x-form.group.text name="city" label="{{ trans_choice('general.cities', 1) }}" :value="$employee->contact->city" not-required />
                            
                            <x-form.group.text name="zip_code" label="{{ trans('general.zip_code') }}" :value="$employee->contact->zip_code" not-required />
                            
                            <x-form.group.text name="state" label="{{ trans('general.state') }}" :value="$employee->contact->state" not-required />
                        
                            <x-form.group.select name="country" label="{{ trans_choice('general.countries', 1) }}" :options="trans('countries')" :selected="$employee->contact->country" form-group-class="sm:col-span-3" model="form.country" not-required />
                        </x-slot>
                    </x-form.section>
                @stack('card_address_end')

                @stack('card_salary_start')
                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans('employees::employees.salary') }}" description="{{ trans('employees::employees.form_description.salary') }}" />
                        </x-slot>

                        <x-slot name="body">
                            <x-form.group.money name="amount" label="{{ trans('general.amount') }}" :value="$employee->amount" autofocus="autofocus" :currency="$currency" dynamicCurrency="currency" />

                            <x-form.group.select name="salary_type" label="{{ trans_choice('general.types', 1) }}" :selected="$employee->salary_type ?? setting('employees.default_salary_type', 'monthly')" :options="$salary_types" form-group-class="sm:col-span-3" />

                            <x-form.group.select name="currency_code" label="{{ trans_choice('general.currencies', 1) }}" :options="$currencies" :selected="$employee->contact->currency_code" change="onChangeCurrency" />

                            <x-form.group.text name="tax_number" label="{{ trans('general.tax_number') }}" :value="$employee->contact->tax_number" not-required />

                            <x-form.group.text name="bank_account_number" label="{{ trans('employees::employees.bank_account_number') }}" not-required />
                        
                            <x-form.group.date name="hired_at" label="{{ trans('employees::employees.hired_at') }}" icon="calendar_today" value="{{ Date::parse($employee->hired_at)->toDateString() }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" />
                        </x-slot>
                    </x-form.section>
                @stack('card_salary_end')

                @stack('card_attachments_start')
                    <x-form.section>
                        <x-slot name="head">
                            <x-form.section.head title="{{ trans('general.attachment') }}" description="{{ trans('employees::employees.form_description.attachment') }}" />
                        </x-slot>

                        <x-slot name="body">
                            <x-form.group.file
                                name="attachment"
                                label="{{ trans('general.attachment') }}"
                                singleWidthClasses
                                not-required
                                dropzone-class="w-100"
                                multiple="multiple"
                                :options="['acceptedFiles' => $file_types]"
                                form-group-class="sm:col-span-6"
                            />
                        </x-slot>
                    </x-form.section>
                @stack('card_attachments_end')

                <x-form.group.switch name="enabled" :value="$employee->contact->enabled" label="{{ trans('general.enabled') }}" />

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="employees.employees.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    @push('scripts_start')
        <script>
            var can_login_errors = {
                valid: '{!! trans('validation.required', ['attribute' => 'email']) !!}',
                email: '{{ trans('customers.error.email') }}'
            };
        </script>
    @endpush

    <x-script alias="employees" file="employees" />
</x-layouts.admin>
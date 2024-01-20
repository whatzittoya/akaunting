<x-layouts.admin>
    <x-slot name="title">
        {{ $employee->contact->name }}
    </x-slot>

    <x-slot name="status">
        @if (! $employee->contact->enabled)
            <x-index.disable text="{{ trans_choice('employees::general.employees', 1) }}" />
        @endif
    </x-slot>

    <x-slot name="favorite"
        title="{{ $employee->contact->name }}"
        icon="groups"
        :route="['employees.employees.show', $employee->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('employees.employees.edit', $employee->id) }}">
            {{ trans('general.edit') }}
        </x-link>

        <x-dropdown id="item-show">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @can('delete-employees-employees')
                <x-delete-link :model="$employee" route="employees.employees.destroy" :text="trans_choice('employees::general.employees', 1)" />
            @endcan
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <x-show.container>
            <x-show.summary>
                <x-show.summary.left>
                    @stack('employee_profile_start')
                        <x-slot name="avatar">
                            @if ($employee->contact->logo)
                                @if (is_object($employee->contact->logo))
                                    <img src="{{ Storage::url($employee->contact->logo->id) }}" class="absolute w-12 h-12 rounded-full mr-2 hidden lg:block" alt="{{ $employee->contact->name }}" title="{{ $employee->contact->name }}">
                                @else
                                    <img src="{{ asset('public/img/user.svg') }}" class="absolute w-12 h-12 rounded-full mr-2 hidden lg:block" alt="{{ $employee->contact->name }}"/>
                                @endif

                                {{ $employee->contact->initials }}
                            @else
                                {{ $employee->contact->initials }}
                            @endif
                        </x-slot>

                        <span>{{ $employee->email }}</span>
                        <span>{{ $employee->contact->phone }}</span>
                    @stack('employee_profile_end')
                </x-show.summary.left>
            </x-show.summary>

            <x-show.content>
                @stack('employee_content_start')
                    <x-show.content.left>
                        @stack('name_input_start')
                        @stack('name_input_end')

                        @stack('email_input_start')
                        @stack('email_input_end')

                        @stack('logo_input_start')
                        @stack('logo_input_end')

                        @stack('phone_input_start')
                        @stack('phone_input_end')

                        @stack('amount_input_start')
                        @stack('employee_amount_start')
                            @if ($employee->amount)
                                <div class="flex flex-col text-sm mb-5">
                                    <div class="font-medium">{{ trans('general.amount') }}</div>
                                    <span>
                                        <x-money :amount="$employee->amount" :currency="$employee->contact->currency_code" convert />
                                    </span>
                                </div>
                            @endif
                        @stack('employee_amount_end')
                        @stack('amount_input_end')

                        @stack('salary_type_input_start')
                        @stack('employee_salary_type_start')
                            @if ($employee->amount && $employee->salary_type)
                                <div class="flex flex-col text-sm mb-5">
                                    <div class="font-medium">{{ trans('employees::general.salary_type') }}</div>
                                    <span>{{ trans('employees::employees.salary_types.' . $employee->salary_type) }}</span>
                                </div>
                            @endif
                        @stack('employee_salary_type_end')
                        @stack('salary_type_input_end')

                        @stack('birth_day_input_start')
                        @stack('employee_birth_day_start')
                            @if ($employee->birth_day)
                                <div class="flex flex-col text-sm mb-5">
                                    <div class="font-medium">{{ trans('employees::employees.birth_day') }}</div>
                                    <span>
                                        <x-date date="{{ $employee->birth_day }}" />
                                    </span>
                                </div>
                            @endif
                        @stack('employee_birth_day_end')
                        @stack('birth_day_input_end')

                        @stack('gender_input_start')
                        @stack('employee_gender_start')
                            @if ($employee->gender)
                                <div class="flex flex-col text-sm mb-5">
                                    <div class="font-medium">{{ trans('employees::employees.gender') }}</div>
                                    <span>{{ $employee->gender }}</span>
                                </div>
                            @endif
                        @stack('employee_gender_end')
                        @stack('gender_input_end')

                        @stack('department_id_input_start')
                        @stack('employee_department_start')
                            @if ($employee->department->name)
                                <div class="flex flex-col text-sm mb-5">
                                    <div class="font-medium">{{ trans_choice('employees::general.departments', 1) }}</div>
                                    <span>{{ $employee->department->name }}</span>
                                </div>
                            @endif
                        @stack('employee_department_end')
                        @stack('department_id_input_end')

                        @stack('address_input_start')
                        @stack('employee_address_start')
                            @if ($employee->contact->address)
                                <div class="flex flex-col text-sm mb-">
                                    <div class="font-medium">{{ trans('general.address') }}</div>
                                    <span>{{ $employee->contact->address }}<br>{{ $employee->contact->location }}</span>
                                </div>
                            @endif
                        @stack('employee_address_end')
                        @stack('address_input_end')

                        @stack('city_input_start')
                        @stack('city_input_end')

                        @stack('zip_code_input_start')
                        @stack('zip_code_input_end')

                        @stack('state_input_start')
                        @stack('state_input_end')

                        @stack('country_input_start')
                        @stack('country_input_end')

                        @stack('currency_code_input_start')
                        @stack('currency_code_input_end')

                        @stack('tax_number_input_start')
                        @stack('tax_number_input_end')

                        @stack('bank_account_number_input_start')
                        @stack('bank_account_number_input_end')

                        @stack('hired_at_input_start')
                        @stack('hired_at_input_end')

                        @stack('attachment_input_start')
                        @stack('attachment_input_end')
                    </x-show.content.left>
                @stack('employee_content_end')

                <x-show.content.right>
                    <x-tabs active="payroll">
                        <x-slot name="navs">
                            @stack('employee_payroll_tab_start')
                                <x-tabs.nav
                                    id="payroll"
                                    name="{{ trans('employees::general.payroll') }}"
                                    active
                                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                                />
                            @stack('employee_payroll_tab_end')

                            @stack('employee_assets_tab_start')
                                <x-tabs.nav
                                    id="assets"
                                    name="{{ trans('employees::general.assets') }}"
                                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                                />
                            @stack('employee_assets_tab_end')

                            @stack('employee_leaves_tab_start')
                                <x-tabs.nav
                                    id="leaves"
                                    name="{{ trans('employees::general.leaves') }}"
                                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                                />
                            @stack('employee_leaves_tab_end')

                            @stack('employee_expenses_tab_start')
                                <x-tabs.nav
                                    id="expenses"
                                    name="{{ trans('employees::general.expense_claims') }}"
                                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                                />
                            @stack('employee_expenses_tab_end')

                            @php($attachment = $employee->attachment ?: [])
                            @if ($attachment)
                                @stack('employee_attachment_tab_start')
                                    <x-tabs.nav
                                        id="attachment"
                                        name="{{ trans_choice('general.attachments', 2) }}"
                                        class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                                    />
                                @stack('employee_attachment_tab_end')
                            @endif
                            </x-slot>

                            <x-slot name="content">
                                @stack('employee_payroll_tab_content_start')
                                    <x-tabs.tab id="payroll">
                                        @if ($payroll)
                                            @stack('payroll_employee_content')
                                        @else
                                            <x-show.no-records 
                                                :description="trans('employees::employees.suggestion.payroll')" 
                                                :url="route('apps.app.show', 'payroll')" 
                                                :text-action="trans('modules.learn_more')" 
                                                image="modules/Employees/Resources/assets/img/suggestions/payroll.png"
                                            />
                                        @endif
                                    </x-tabs.tab>
                                @stack('employee_payroll_tab_content_end')

                                @stack('employee_assets_tab_content_start')
                                    <x-tabs.tab id="assets">
                                        @if ($assets)
                                            @stack('assets_employee_content')
                                        @else
                                            <x-show.no-records 
                                                :description="trans('employees::employees.suggestion.assets')" 
                                                :url="route('apps.app.show', 'assets')" 
                                                :text-action="trans('modules.learn_more')" 
                                                image="modules/Employees/Resources/assets/img/suggestions/assets.png"
                                            />
                                        @endif
                                    </x-tabs.tab>
                                @stack('employee_assets_tab_content_end')

                                @stack('employee_leaves_tab_content_start')
                                    <x-tabs.tab id="leaves">
                                        @if ($leaves)
                                            @stack('leaves_employee_content')
                                        @else
                                            <x-show.no-records 
                                                :description="trans('employees::employees.suggestion.leaves')" 
                                                :url="route('apps.app.show', 'leaves')" 
                                                :text-action="trans('modules.learn_more')" 
                                                image="modules/Employees/Resources/assets/img/suggestions/leaves.png"
                                            />
                                        @endif
                                    </x-tabs.tab>
                                @stack('employee_leaves_tab_content_end')

                                @stack('employee_expenses_tab_content_start')
                                    <x-tabs.tab id="expenses">
                                        @if ($expenses)
                                            @stack('expenses_employee_content')
                                        @else
                                            <x-show.no-records 
                                                :description="trans('employees::employees.suggestion.expense_claims')" 
                                                :url="route('apps.app.show', 'expenses')" 
                                                :text-action="trans('modules.learn_more')" 
                                                image="modules/Employees/Resources/assets/img/suggestions/expenses.png"
                                            />
                                        @endif
                                    </x-tabs.tab>
                                @stack('employee_expenses_tab_content_end')

                                @stack('employee_attachment_tab_content_start')
                                    <x-tabs.tab id="attachment">
                                        @foreach ($attachment as $file)
                                            <x-media.file :file="$file"></x-media.file>
                                        @endforeach
                                    </x-tabs.tab>
                                @stack('employee_attachment_tab_content_end')
                            </x-slot>
                        </x-tabs>
                </x-show.content.right>
            </x-show.content>
        </x-show.container>
    </x-slot>

    <x-script alias="employees" file="employees" />
</x-layouts.admin>

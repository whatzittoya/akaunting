<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('employees::general.employees', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('employees::general.employees', 2) }}"
        icon="groups"
        route="employees.employees.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-employees-employees')
            <x-link href="{{ route('employees.employees.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('employees::general.employees', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @can('create-employees-employees')
                <x-dropdown.link href="{{ route('import.create', ['employees', 'employees']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('employees.employees.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($employees->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="Modules\Employees\Models\Employee"
                    bulk-action="Modules\Employees\BulkActions\Employees"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th kind="bulkaction">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-3/12">
                                <x-sortablelink column="contact.name" title="{{ trans('general.name') }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                <x-sortablelink column="contact.email" title="{{ trans('general.email') }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                <x-sortablelink column="department.name" title="{{ trans_choice('employees::general.departments', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-3/12" kind="right">
                                <x-sortablelink column="hired_at" title="{{ trans('employees::employees.hired_at') }}" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($employees as $item)
                            <x-table.tr href="{{ route('employees.employees.show', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-3/12 truncate">
                                    <div class="flex items-center">
                                        @if (is_object($item->contact->logo))
                                            <img src="{{ Storage::url($item->contact->logo->id) }}" class="w-6 h-6 bottom-6 rounded-full mr-2 hidden lg:block" alt="{{ $item->contact->name }}" title="{{ $item->contact->name }}">
                                        @else
                                            <img src="{{ asset('public/img/user.svg') }}" class="w-6 h-6 bottom-6 rounded-full mr-2 hidden lg:block" alt="{{ $item->contact->name }}"/>
                                        @endif

                                        <div class="font-bold truncate">
                                            {{ $item->name }}
                                        </div>

                                        @if (! $item->contact->enabled)
                                            <x-index.disable text="{{ trans_choice('employees::general.employees', 1) }}" />
                                        @endif
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-3/12 text-left" hidden-mobile>
                                    {{ $item->email }}
                                </x-table.td>

                                <x-table.td class="w-3/12 text-left" hidden-mobile>
                                    {{ $item->department->name ?? 'N/A' }}
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-3/12" kind="right">
                                    <x-date date="{{ $item->hired_at }}" />
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$employees" />
            </x-index.container>
        @else
            <x-empty-page group="employees" page="employees" docs-category="hr" />
        @endif
    </x-slot>

    <x-script alias="employees" file="employees" />
</x-layouts.admin>
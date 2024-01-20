@if ($departments->count())
    <div class="w-full">
        <div class="flex flex-wrap flex-col sm:flex-row sm:items-center justify-end sm:space-x-2 sm:rtl:space-x-reverse">
            @can('create-employees-departments')
                <x-link href="{{ route('employees.settings.departments.create') }}" kind="primary">
                    {{ trans('general.title.new', ['type' => trans_choice('employees::general.departments', 1)]) }}
                </x-link>
            @endcan
        </div>
    </div>
    <x-index.search
        search-string="Modules\Employees\Models\Department"
        bulk-action="Modules\Employees\BulkActions\Departments"
    />

    <x-table>
        <x-table.thead>
            <x-table.tr>
                <x-table.th kind="bulkaction">
                    <x-index.bulkaction.all />
                </x-table.th>

                <x-table.th class="w-5/12">
                    <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                </x-table.th>

                <x-table.th class="w-5/12">
                    {{ trans('employees::general.manager') }}
                </x-table.th>

                <x-table.th class="w-2/12">
                    {{ trans('employees::general.total', ['type' => trans('employees::general.name')]) }}
                </x-table.th>
            </x-table.tr>
        </x-table.thead>

        <x-table.tbody x-data="setCollapse()">
            @foreach ($departments as $item)
                <x-table.tr href="{{ route('employees.settings.departments.edit', $item->id) }}" data-table-list class="relative flex items-center border-b hover:bg-gray-100 px-1 group transition-[height]">
                    <x-table.td kind="bulkaction">
                        <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                    </x-table.td>

                    <x-table.td class="w-5/12 truncate">
                        @if ($item->sub_departments->count())
                            <div class="flex items-center font-bold">
                                {{ $item->name }}

                                <button type="button" class="leading-none align-text-top" node="child-{{ $item->id }}" x-on:click.stop="toggleSub('child-{{ $item->id }}'), $event.target.classList.toggle('rotate-90')">
                                    <span class="material-icons transform transition-all text-lg leading-none align-middle">navigate_next</span>
                                </button>
                            </div>
                        @else
                            <span class="font-bold">{{ $item->name }}</span>
                        @endif

                        @if (! $item->enabled)
                            <x-index.disable text="{{ trans_choice('employees::general.departments', 1) }}" />
                        @endif
                    </x-table.td>

                    <x-table.td class="w-5/12 text-left">
                        {{ $item->user->name ?? trans('general.na') }}
                    </x-table.td>

                    <x-table.td class="w-2/12 text-left">
                        {{ $item->employees->count() }}
                    </x-table.td>

                    <x-table.td kind="action">
                        <x-table.actions :model="$item" />
                    </x-table.td>
                </x-table.tr>
                @foreach($item->sub_departments as $sub_department)
                    @include('employees::partials.sub_department', ['parent_department' => $item, 'sub_department' => $sub_department, 'tree_level' => 1])
                @endforeach
            @endforeach
        </x-table.tbody>
    </x-table>

    <x-pagination :items="$departments" />
@else
    <div class="mt-4">
        <x-empty-page group="employees" page="departments" docs-category="hr" hide-button-import />
    </div>
@endif
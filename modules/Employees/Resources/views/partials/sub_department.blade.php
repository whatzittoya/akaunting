@if ($sub_department->sub_departments)
    <x-table.tr data-collapse="child-{{ $parent_department->id }}" class="relative flex items-center hover:bg-gray-100 px-1 group transition-height collapse-sub" href="{{ route('employees.settings.departments.edit', $sub_department->id) }}">
        <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
            <x-index.bulkaction.single id="{{ $sub_department->id }}" name="{{ $sub_department->name }}" />
        </x-table.td>

        <x-table.td class="w-5/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-medium text-black truncate" style="padding-left: {{ $tree_level * 15 }}px;">
            @if ($sub_department->sub_departments->count())
                <div class="flex items-center font-bold">
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>

                    {{ $sub_department->name }}

                    <button type="button" class="leading-none align-text-top" node="child-{{ $sub_department->id }}" x-on:click.stop="toggleSub('child-{{ $sub_department->id }}'), $event.target.classList.toggle('rotate-90')">
                        <span class="material-icons transform transition-all text-lg leading-none align-middle">navigate_next</span>
                    </button>
                </div>
            @else
                <div class="flex items-center font-bold">
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>
                    {{ $sub_department->name }}
                </div>
            @endif

            @if (! $sub_department->enabled)
                <x-index.disable text="{{ trans_choice('employees::general.departments', 1) }}" />
            @endif
        </x-table.td>

        <x-table.td class="w-5/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-normal text-black cursor-pointer truncate">
            {{ $sub_department->user->name ?? trans('general.na') }}
        </x-table.td>

        <x-table.td class="ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-normal text-black cursor-pointer w-2/12 relative">
            {{ $sub_department->employees->count() }}
        </x-table.td>

        <x-table.td class="p-0" override="class">
            <x-table.actions :model="$sub_department" />
        </x-table.td>
    </x-table.tr>

    @php
        $parent_department = $sub_department;
        $tree_level++;
    @endphp

    @foreach($sub_department->sub_departments as $sub_department)
        @include('employees::partials.sub_department', ['parent_department' => $parent_department, 'sub_department' => $sub_department, 'tree_level' => $tree_level])
    @endforeach
@endif

<div id="widget-{{ $class->model->id }}" class="w-full my-8 px-12">
    <div class="pb-2 my-4 lg:my-0">
        <div class="flex justify-between font-medium mb-2">
            <h2 class="text-black" title="{{ $class->model->name }}">
                {{ $class->model->name }}
            </h2>
        </div>
        <span class="h-6 block border-b text-black-400 text-xs truncate">
            {{ $class->getDescription() }}
        </span>
    </div>
    
    <div class="flex flex-col lg:flex-row mt-3">
        <div class="w-full lg:w-11/12">

            {{-- @dd($chart->container()) --}}
            {!! $chart->container() !!}
        </div>

        <div class="w-full lg:w-1/12 mt-11 space-y-2">
            <div class="flex flex-col items-center justify-between text-center">
                <div class="flex justify-end lg:block text-lg">
                    {{ $totals }}
                </div>

                <span class="text-green text-xs">{{ trans('employees::general.name') }}</span>
            </div>
        </div>
    </div>
</div>

@push('body_scripts')
    {!! $chart->script() !!}
@endpush

<x-layouts.admin>
    <x-slot name="title">{{ trans('employees::general.name') }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans('employees::general.name') }}"
        icon="groups"
        route="employees.settings.index"
    ></x-slot>

    <x-slot name="content"> 
        <x-tabs class="mt-14" active="general">
            <x-slot name="navs">
                <x-tabs.nav
                    id="general"
                    name="{{ trans('general.general') }}"
                    active
                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                />
            
                <x-tabs.nav
                    id="departments"
                    name="{{ trans_choice('employees::general.departments', 2) }}"
                    class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b swiper-slide"
                />
            </x-slot>

            <x-slot name="content">
                <x-tabs.tab id="general">
                    @include('employees::settings.edit')
                </x-tabs.tab>

                <x-tabs.tab id="departments" class="mt-4">
                    @include('employees::settings.departments.index')
                </x-tabs.tab>
            </x-slot>
        </x-tabs>
    </x-slot>

    @push('scripts_start')
        <script type="text/javascript">
            function setCollapse() {
                return {
                    toggleSub(key, event) {
                        let isExpanded = document.querySelectorAll(`[data-collapse="${key}"]` + '.active-collapse').length > 0;

                        if (isExpanded) {
                            this.collapseSub(key, event);
                        } else {
                            this.expandSub(key, event);
                        }
                    },

                    collapseSub(key, event) {
                        document.querySelectorAll(`[data-collapse="${key}"]` + '.active-collapse').forEach(function (element) {
                            element.classList.toggle('active-collapse');
                            element.classList.toggle('collapse-sub');
                        });

                        // if collapsed key has childs(table row constantly), they will be collapsed as well
                        document.querySelectorAll(`[data-collapse="${key}"]` + " button[node|='child']").forEach(function (element) {
                            document.querySelectorAll('[data-collapse]').forEach((child) => {
                                if (element.parentElement.parentElement.parentElement.classList.contains('collapse-sub') && child.getAttribute('data-collapse') == element.getAttribute('node')) {
                                    child.classList.remove('active-collapse');
                                    child.classList.add('collapse-sub');
                                    element.children[0].classList.remove('rotate-90');
                                }
                            });
                        });
                    },

                    expandSub(key, event) {
                        document.querySelectorAll(`[data-collapse="${key}"]`).forEach(function (element) {
                            element.classList.toggle('active-collapse');
                            element.classList.toggle('collapse-sub');
                        });
                    }
                }
            }
        </script>
    @endpush

    <x-script alias="employees" file="settings" />
</x-layouts.admin>
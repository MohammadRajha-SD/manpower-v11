<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_service_plural')" :desc="__('lang.e_service_desc')"
        :table_name="__('lang.e_service_table')" :route="route('admin.services.index')" />

    <x-admins.cards.content :name1="__('lang.e_service_table')" :name2="__('lang.e_service_create')"
        route1="admin.services.index" route2="admin.services.create">
        @push('css_lib')
        @include('admins.layouts.datatables_css')
        @endpush

        {!! $dataTable->table(['width' => '100%']) !!}

        @push('scripts_lib')
        @include('admins.layouts.datatables_js')
        {!! $dataTable->scripts() !!}
        @endpush

    </x-admins.cards.content>
</x-admin-layout>
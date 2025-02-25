<x-admin-layout>
    <x-admins.cards.header :name="__('lang.availability_hour_plural')" :desc="__('lang.availability_hour_desc')" :table_name="__('lang.availability_hour_table')"
        :route="route('admin.provider-schedules.index')" />

    <x-admins.cards.content :name1="__('lang.availability_hour_table')" :name2="__('lang.availability_hour_create')" route1="admin.provider-schedules.index"
        route2="admin.provider-schedules.create">
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
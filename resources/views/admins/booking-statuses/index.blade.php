<x-admin-layout>
    <x-admins.cards.header :name="__('lang.booking_status_plural')" :desc="__('lang.booking_status_desc')"
        :table_name="__('lang.booking_status_table')" :route="route('admin.booking-statuses.index')" />

    <x-admins.cards.content :name1="__('lang.booking_status_table')" :name2="__('lang.booking_status_create')"
        route1="admin.booking-statuses.index" route2="admin.booking-statuses.create">
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
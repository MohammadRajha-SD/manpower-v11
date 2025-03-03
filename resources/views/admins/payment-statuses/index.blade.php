<x-admin-layout>
    <x-admins.cards.header :name="__('lang.payment_status_plural')" :desc="__('lang.payment_status_desc')"
        :table_name="__('lang.payment_status_table')" :route="route('admin.payment-statuses.index')" />

    <x-admins.cards.content :name1="__('lang.payment_status_table')" :name2="__('lang.payment_status_create')"
        route1="admin.payment-statuses.index" route2="admin.payment-statuses.create">
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
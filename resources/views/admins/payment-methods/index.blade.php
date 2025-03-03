<x-admin-layout>
    <x-admins.cards.header :name="__('lang.payment_method_plural')" :desc="__('lang.payment_method_desc')"
        :table_name="__('lang.payment_method_table')" :route="route('admin.payment-statuses.index')" />

    <x-admins.cards.content :name1="__('lang.payment_method_table')" :name2="__('lang.payment_method_create')"
        route1="admin.payment-methods.index" route2="admin.payment-methods.create">
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
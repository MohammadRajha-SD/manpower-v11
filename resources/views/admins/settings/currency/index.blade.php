<x-admin-layout>
    <x-admins.cards.header :name="__('lang.currency')" :desc="__('lang.currency_desc')"
        :table_name="__('lang.currency_table')" :route="route('admin.currencies.index')" />

    <x-admins.cards.content :name1="__('lang.currency_table')" :name2="__('lang.currency_create')"
        route1="admin.currencies.index" route2="admin.currencies.create">
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
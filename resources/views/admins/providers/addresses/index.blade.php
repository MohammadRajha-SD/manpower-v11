<x-admin-layout>
    <x-admins.cards.header :name="__('lang.address_plural')" :desc="__('lang.address_desc')"
        :table_name="__('lang.address_table')" :route="route('admin.addresses.index')" />

    <x-admins.cards.content :name1="__('lang.address_table')" :name2="__('lang.address_create')"
        route1="admin.addresses.index" route2="admin.addresses.create">
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
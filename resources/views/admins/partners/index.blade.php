<x-admin-layout>
    <x-admins.cards.header :name="__('lang.partner_plural')" :desc="__('lang.partner_desc')"
        :table_name="__('lang.partner_table')" :route="route('admin.partners.index')" />

    <x-admins.cards.content :name1="__('lang.partner_table')" :name2="__('lang.partner_create')"
        route1="admin.partners.index" route2="admin.partners.create">
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
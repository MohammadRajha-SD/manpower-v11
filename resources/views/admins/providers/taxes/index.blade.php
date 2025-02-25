<x-admin-layout>
    <x-admins.cards.header :name="__('lang.tax_plural')" :desc="__('lang.tax_desc')" :table_name="__('lang.tax_table')"
        :route="route('admin.taxes.index')" />

    <x-admins.cards.content :name1="__('lang.tax_table')" :name2="__('lang.tax_create')" route1="admin.taxes.index"
        route2="admin.taxes.create">
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
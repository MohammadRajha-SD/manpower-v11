<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_provider_type_plural')" :desc="__('lang.e_provider_type_desc')"
        :table_name="__('lang.e_provider_type_table')" :route="route('admin.providers.index')" />

    <x-admins.cards.content :name1="__('lang.e_provider_type_table')" :name2="__('lang.e_provider_type_create')"
        route1="admin.provider-types.index" route2="admin.provider-types.create">
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
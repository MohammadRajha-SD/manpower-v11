<x-admin-layout>
    <x-admins.cards.header :name="__('lang.user')" :desc="__('lang.user_table')" :table_name="__('lang.user_table')"
        :route="route('admin.users.index')" />

    <x-admins.cards.content :name1="__('lang.user_table')" :name2="__('lang.user_create')" route1="admin.users.index"
        route2="admin.users.create">
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
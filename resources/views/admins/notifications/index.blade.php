<x-admin-layout>
    <x-admins.cards.header :name="__('lang.notification_plural')" :desc="__('lang.notification_desc')"
        :table_name="__('lang.notification_table')" :route="route('admin.notifications.index')" />

    <x-admins.cards.content :name1="__('lang.notification_table')" route1="admin.notifications.index"  :isCreateMode="false">
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
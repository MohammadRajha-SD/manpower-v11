<x-admin-layout>
    <x-admins.cards.header :name="__('lang.app_setting')" :desc="__('lang.setting_desc')" :route="route('admin.settings.index')" />

    <x-admins.cards.content :name1="__('lang.app_setting_globals')" route1="admin.settings.index" :isCreateMode="false">
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
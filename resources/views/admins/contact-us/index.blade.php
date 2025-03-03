<x-admin-layout>
    <x-admins.cards.header :name="__('lang.contact_us_plural')" :desc="__('lang.contact_us_desc')"
        :table_name="__('lang.contact_us_table')" :route="route('admin.contact-us.index')" />

    <x-admins.cards.content :name1="__('lang.contact_us_table')" route1="admin.contact-us.index"  :isCreateMode="false">
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
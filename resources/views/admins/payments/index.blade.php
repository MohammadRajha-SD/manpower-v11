<x-admin-layout>
    <x-admins.cards.header :name="__('lang.payment_plural')" :desc="__('lang.payment_desc')"
        :table_name="__('lang.payment_table')" :route="route('admin.payments.index')" />

    <x-admins.cards.content :name1="__('lang.payment_table')" route1="admin.payments.index"  :isCreateMode="false">
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
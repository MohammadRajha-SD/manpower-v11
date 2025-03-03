<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_provider_payout_plural')" :desc="__('lang.e_provider_payout_desc')"
        :table_name="__('lang.e_provider_payout_table')" :route="route('admin.provider-payouts.index')" />

    <x-admins.cards.content :name1="__('lang.e_provider_payout_table')" route1="admin.provider-payouts.index"  :isCreateMode="false">
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
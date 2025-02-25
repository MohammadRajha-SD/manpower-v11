<x-admin-layout>
    <x-admins.cards.header :name="__('lang.coupon_plural')" :desc="__('lang.coupon_desc')" :table_name="__('lang.coupon_table')"
        :route="route('admin.coupons.index')" />

    <x-admins.cards.content :name1="__('lang.coupon_table')" :name2="__('lang.coupon_create')" route1="admin.coupons.index"
        route2="admin.coupons.create">
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
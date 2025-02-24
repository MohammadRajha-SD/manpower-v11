<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_service_review_plural')" :desc="__('lang.e_service_review_desc')"
        :table_name="__('lang.e_service_review_table')" :route="route('admin.service-reviews.index')" />

    <x-admins.cards.content :name1="__('lang.e_service_review_table')" :name2="__('lang.e_service_review_create')"
        route1="admin.service-reviews.index" route2="admin.service-reviews.create">
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
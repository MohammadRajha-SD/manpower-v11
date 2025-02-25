<x-admin-layout>
    <x-admins.cards.header :name="__('lang.faq_plural')" :desc="__('lang.faq_desc')" :table_name="__('lang.faq_table')"
        :route="route('admin.provider-types.index')" />

    <x-admins.cards.content :name1="__('lang.faq_table')" :name2="__('lang.faq_create')" route1="admin.faqs.index"
        route2="admin.faqs.create">
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
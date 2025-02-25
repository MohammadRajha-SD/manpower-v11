<x-admin-layout>
    <x-admins.cards.header :name="__('lang.faq_category_plural')" :desc="__('lang.faq_category_desc')"
        :table_name="__('lang.faq_category_table')" :route="route('admin.faq-categories.index')" />

    <x-admins.cards.content :name1="__('lang.faq_category_table')" :name2="__('lang.faq_category_create')"
        route1="admin.faq-categories.index" route2="admin.faq-categories.create">
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
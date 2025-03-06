<x-admin-layout>
    <x-admins.cards.header :name="__('lang.slide_plural')" :desc="__('lang.slide_desc')" :table_name="__('lang.slide_table')"
        :route="route('admin.slides.index')" />

    <x-admins.cards.content :name1="__('lang.slide_table')" :name2="__('lang.slide_create')" route1="admin.slides.index"
        route2="admin.slides.create">
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
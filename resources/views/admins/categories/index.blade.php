<x-admin-layout>
    <x-admins.cards.header :name="__('lang.category_plural')" :desc="__('lang.category_desc')"
        :table_name="__('lang.category_table')" :route="route('admin.categories.index')" />

    <x-admins.cards.content :name1="__('lang.category_table')" :name2="__('lang.category_create')"
        route1="admin.categories.index" route2="admin.categories.create">
        @push('css_lib')
        @include('admins.layouts.datatables_css')
        @endpush

        <div class="w-full overflow-auto">
            {!! $dataTable->table(['width' => '100%']) !!}
        </div>
        
        @push('scripts_lib')
        @include('admins.layouts.datatables_js')
        {!! $dataTable->scripts() !!}
        @endpush

    </x-admins.cards.content>
</x-admin-layout>
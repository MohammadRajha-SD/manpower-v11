<x-admin-layout>
    <x-admins.cards.header :name="__('lang.most_populars_plural')" :desc="__('lang.most_popular_desc')"
        :table_name="__('lang.most_popular_table')" :route="route('admin.most-populars.index')" />

    <x-admins.cards.content :name1="__('lang.most_popular_table')" :name2="__('lang.most_popular_create')"
        route1="admin.most-populars.index" route2="admin.most-populars.create">
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
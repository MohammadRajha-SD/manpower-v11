<x-admin-layout>
    <x-admins.cards.header :name="__('lang.pack')" :desc="__('lang.pack_management')" :table_name="__('lang.pack')"
        :route="route('admin.packs.index')" />

    <x-admins.cards.content :name1="__('lang.pack_management')" :name2="__('lang.create_pack')"
        route1="admin.packs.index" route2="admin.packs.create">
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
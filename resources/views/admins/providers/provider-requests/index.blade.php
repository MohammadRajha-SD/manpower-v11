<x-admin-layout>
    <x-admins.cards.header :name="__('lang.requested_e_providers_plural')" :desc="__('lang.requested_e_providers_desc')"
        :table_name="__('lang.requested_e_providers_table')" :route="route('admin.provider-requests.index')" />

    <x-admins.cards.content :name1="__('lang.requested_e_providers_table')" :name2="__('lang.e_provider_create')"
        route1="admin.provider-requests.index" route2="admin.providers.create">
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
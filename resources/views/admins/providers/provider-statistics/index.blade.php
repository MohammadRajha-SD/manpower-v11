<x-admin-layout>
    <x-admins.cards.header :name="__('lang.award_e_provider_id')" :desc="__('lang.provider_statistique')" :route="route('admin.provider-statistics.index')" :table_name="__('lang.provider_statistique')" />

    <x-admins.cards.content :name1="__('lang.provider_statistique')" :isCreateMode="false" route1="admin.provider-statistics.index" >
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
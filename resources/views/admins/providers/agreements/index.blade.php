<x-admin-layout>
    <x-admins.cards.header :name="__('lang.agreement_plural')" :desc="__('lang.agreement_desc')"
        :table_name="__('lang.agreement_table')" :route="route('admin.provider-requests.index')" />

    <x-admins.cards.content :name1="__('lang.agreement_table')" :name2="__('lang.agreement_create')"
        route1="admin.agreements.index" route2="admin.agreements.create" :isAgreementAction="true"
        :isCreateMode="false">
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
    
    <livewire:agreement-provider-details />
    <livewire:agreement-provider-create />
</x-admin-layout>
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.subscriptions')" :desc="__('lang.subscription_list')"
        :route="route('admin.subscriptions.index')" />

    <x-admins.cards.content :name1="__('lang.subscription_list')" route1="admin.subscriptions.index"
        route2="admin.subscriptions.create" :name2="__('lang.create_subscription')">
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
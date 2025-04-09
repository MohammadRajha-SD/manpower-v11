<x-admin-layout>
    <x-admins.cards.header :name="__('lang.booking_plural')" :desc="__('lang.booking_desc')"
        :table_name="__('lang.booking_table')" :route="route('admin.bookings.index')" />

    <x-admins.cards.content :name1="__('lang.booking_table')" route1="admin.bookings.index" :isCreateMode="false">
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
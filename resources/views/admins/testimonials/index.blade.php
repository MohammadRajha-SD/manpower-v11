<x-admin-layout>
    <x-admins.cards.header :name="__('lang.testimonial_plural')" :desc="__('lang.testimonial_desc')"
        :table_name="__('lang.testimonial_table')" :route="route('admin.testimonials.index')" />

    <x-admins.cards.content :name1="__('lang.testimonial_table')" :name2="__('lang.testimonial_create')"
        route1="admin.testimonials.index" route2="admin.testimonials.create">
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
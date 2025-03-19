@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.availability_hour_plural')" :desc="__('lang.availability_hour_desc')"
        :table_name="__('lang.availability_hour_table')" :route="route('admin.provider-schedules.index')" />

    <x-admins.cards.content :name1="__('lang.availability_hour_table')" :name2="__('lang.availability_hour_create')"
        route1="admin.provider-schedules.index" route2="admin.provider-schedules.create" :isEditMode="true" :name3="__('lang.availability_hour_edit')" :route3="['admin.provider-schedules.edit', $id]">
        <livewire:weekly-working-hours :id="$id"/>
    </x-admins.cards.content>
    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    @endpush
</x-admin-layout>
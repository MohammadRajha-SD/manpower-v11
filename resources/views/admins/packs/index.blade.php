<x-admin-layout>
    <x-admins.cards.header :name="__('lang.category_plural')" :desc="__('lang.category_desc')"
        :table_name="__('lang.category_table')" :route="route('admin.categories.index')" />

    <x-admins.cards.content :name1="__('lang.category_table')" :name2="__('lang.category_create')"
        route1="admin.categories.index" route2="admin.categories.create">

        @push('css_lib')
        @include('admins.layouts.datatables_css')
        @endpush

        <table id="categories-table" class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('lang.category_image') }}</th>
                    <th>{{ trans('lang.category_name') }}</th>
                    <th>{{ trans('lang.category_color') }}</th>
                    <th>{{ trans('lang.category_description') }}</th>
                    <th>{{ trans('lang.category_featured') }}</th>
                    <th>{{ trans('lang.category_order') }}</th>
                    <th>{{ trans('lang.category_parent_id') }}</th>
                    <th>{{ trans('lang.category_updated_at') }}</th>
                    <th>{{ trans('lang.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packs as $category)
                <tr>
                    <td>{!! image_item($category) !!}</td>

                    <td>
                        @if($category->featured)
                        {{ $category->name }} <span class='badge bg-{{ setting(' theme_color') }} p-1 m-2'>{{
                            trans('lang.category_featured') }}</span>
                        @else
                        {{ $category->name}}
                        @endif
                    </td>
                    <td>{{ $category->color }}</td>
                    <td>{!! desc_limit($category->desc) !!}</td>
                    <td>{{ $category->featured ? trans('lang.yes') : trans('lang.no') }}</td>
                    <td>{{ $category->order }}</td>
                    <td>
                        @if($category->parent)
                        <a href="{{ route('admin.categories.edit', $category->parent->id) }}">{{
                            $category->parent->name }}</a>
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <form action="{{ route('admin.categories.edit', $category->id) }}" method="GET"
                                style="margin-right: 5px;">
                                <button type="submit" class="btn btn-link text-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">
                        {{ __('lang.no_data_found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="clearfix"></div>
        @if ($packs && count($packs) > 0)
        <div class="d-flex justify-content-center">
            {{ $packs->links() }}
        </div>
        @endif
        @push('scripts_lib')
        @include('admins.layouts.datatables_js')
        @endpush
    </x-admins.cards.content>
</x-admin-layout>
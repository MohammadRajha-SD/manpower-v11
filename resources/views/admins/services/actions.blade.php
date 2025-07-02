<td class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-cogs"></i> {{ __('lang.actions') }}
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('admin.services.edit', $query->id) }}"
                class="btn text-success btn-sm ml-2">
                <i class='far fa-edit'></i> {{ __('lang.edit') }}
            </a>

              <a href="{{ route('admin.services.destroy', $query->id) }}"
                class="btn text-danger btn-sm ml-2 delete-item">
                <i class='fa fa-trash'></i> {{ __('lang.delete') }}
            </a>

            <livewire:service-address-action :id="$query->id" />
        </div>
    </div>
</td>
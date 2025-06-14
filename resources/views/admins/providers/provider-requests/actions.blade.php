<td class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-cogs"></i> {{ __('lang.actions') }}
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('admin.provider-requests.destroy', $request->id) }}"
                class="btn text-danger btn-sm ml-2 delete-item">
                <i class='fa fa-trash'></i> {{ __('lang.delete') }}
            </a>

            @if (!$request->agreement)
            <livewire:agreement-actions :id="$request->id" />
            @endif

            @if ($request->agreement)

            <livewire:agreement-details-action :id="$request->id" />

            <a href="{{ route('admin.provider-requests.send', ['id' => $request->agreement?->uid, 'lang' => 'en']) }}"
                class="btn text-primary btn-sm ml-2">
                <i class="fa fa-paper-plane"></i> {{ __('lang.send') }} - EN
            </a>

            <a href="{{ route('admin.provider-requests.send', ['id' => $request->agreement?->uid, 'lang' => 'ar']) }}"
                class="btn text-primary btn-sm ml-2">
                <i class="fa fa-paper-plane"></i> {{ __('lang.send') }} - AR
            </a>
            @endif
        </div>
    </div>
</td>
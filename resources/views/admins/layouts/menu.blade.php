<x-admins.sidebar-item :name="__('lang.dashboard')" icon="fas fa-tachometer-alt" :activeRoutes="['admin.dashboard']"
    route="dashboard" />

<li class="nav-header">{{trans('lang.app_management')}}</li>

<x-admins.sidebar-items :name="__('lang.e_provider_plural')"
    :routes="[['admin.provider*', 'admin.addresses*', 'admin.taxes*', 'provider-schedules*'], 'menu-open']" icon="fas fa-users-cog">
    <x-admins.sidebar-item :name="__('lang.e_provider_plural')" icon="fas fa-list" :activeRoutes="['admin.providers*']"
        route="providers.index" />
    <x-admins.sidebar-item :name="__('lang.e_provider_type_plural')" icon="fas fa-list"
        :activeRoutes="['admin.provider-types*']" route="provider-types.index" />

    <x-admins.sidebar-item :name="__('lang.address_plural')" icon="fas fa-map-marked-alt"
        :activeRoutes="['admin.addresses*']" route="addresses.index" />

    <x-admins.sidebar-item :name="__('lang.tax_plural')" icon="fas fa-coins" :activeRoutes="['admin.taxes*']"
        route="taxes.index" />

    <x-admins.sidebar-item :name="__('lang.availability_hour_plural')" icon="fas fa-business-time" :activeRoutes="['admin.provider-schedules*']"
        route="provider-schedules.index" />
</x-admins.sidebar-items>

<x-admins.sidebar-item :name="__('lang.category_plural')" icon="fas fa-folder-open"
    :activeRoutes="['admin.categories*']" route="categories.index" />

{{--
<x-admins.sidebar-item :name="__('lang.pack')" icon="fas fa-folder-open" :activeRoutes="['admin.packs*']"
    route="packs.index" /> --}}

<x-admins.sidebar-items :name="__('lang.e_service_plural')"
    :routes="[['admin.services*', 'admin.service-reviews*'], 'menu-open']" icon="fas fa-pencil-ruler">
    <x-admins.sidebar-item :name="__('lang.e_service_table')" icon="fas fa-list" :activeRoutes="['admin.services*']"
        route="services.index" />

    <x-admins.sidebar-item :name="__('lang.e_service_review_plural')" icon="fas fa-comments"
        :activeRoutes="['admin.service-reviews*']" route="service-reviews.index" />
</x-admins.sidebar-items>

<x-admins.sidebar-item :name="__('lang.coupon_plural')" icon="fas fa-ticket-alt"
    :activeRoutes="['admin.coupons*']" route="coupons.index" />
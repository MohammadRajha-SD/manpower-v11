<x-admins.sidebar-item :name="__('lang.dashboard')" icon="fas fa-tachometer-alt" :activeRoutes="['admin.dashboard']"
    route="dashboard" />

<x-admins.sidebar-item :name="__('lang.notification_plural')" icon="fas fa-bell"
    :activeRoutes="['admin.notifications.index']" route="notifications.index" />

<x-admins.sidebar-item :name="__('lang.contact_messages')" icon="fa fa-envelope"
    :activeRoutes="['admin.contact-us.index']" route="contact-us.index" />

<li class="nav-header">{{trans('lang.app_management')}}</li>

{{-- PROVIDERS --}}
<x-admins.sidebar-items :name="__('lang.e_provider_plural')"
    :routes="[['admin.providers*', 'admin.provider-types*','admin.addresses*', 'admin.provider-schedules*', 'admin.subscriptions.*', 'admin.statistics*','admin.provider-requests*'], 'menu-open']"
    icon="fas fa-users-cog">
    <x-admins.sidebar-item :name="__('lang.e_provider_plural')" icon="fas fa-list" :activeRoutes="['admin.providers*']"
        route="providers.index" />

    <x-admins.sidebar-item :name="__('lang.e_provider_type_plural')" icon="fas fa-list"
        :activeRoutes="['admin.provider-types*']" route="provider-types.index" />

    <x-admins.sidebar-item :name="trans('lang.provider_subscriptions')" icon="fas fa-address-card"
        :activeRoutes="['admin.subscriptions*']" route="subscriptions.index" />

    <x-admins.sidebar-item :name="trans('lang.provider_statistique')" icon="fas fa-list-alt"
        :activeRoutes="['admin.provider-statistics*']" route="provider-statistics.index" />

    <x-admins.sidebar-item :name="trans('lang.requested_e_providers_plural')" icon="fas fa-list-alt"
        :activeRoutes="['admin.provider-requests*']" route="provider-requests.index" />

    <x-admins.sidebar-item :name="trans('lang.agreement_plural')" icon="fas fa-file"
        :activeRoutes="['admin.agreements*']" route="agreements.index" />

    <x-admins.sidebar-item :name="__('lang.address_plural')" icon="fas fa-map-marked-alt"
        :activeRoutes="['admin.addresses*']" route="addresses.index" />

    <x-admins.sidebar-item :name="__('lang.availability_hour_plural')" icon="fas fa-business-time"
        :activeRoutes="['admin.provider-schedules*']" route="provider-schedules.index" />
</x-admins.sidebar-items>

{{-- CATEGORIES --}}
<x-admins.sidebar-item :name="__('lang.category_plural')" icon="fas fa-folder-open"
    :activeRoutes="['admin.categories*']" route="categories.index" />

<x-admins.sidebar-item :name="__('lang.most_populars_plural')" icon="fas fa-star"
    :activeRoutes="['admin.most-populars*']" route="most-populars.index" />

{{-- PACKS --}}
<x-admins.sidebar-item :name="__('lang.pack')" icon="fas fa-list-alt" :activeRoutes="['admin.packs*']"
    route="packs.index" />

{{-- SERVICES --}}
<x-admins.sidebar-items :name="__('lang.e_service_plural')"
    :routes="[['admin.services*', 'admin.service-reviews*'], 'menu-open']" icon="fas fa-pencil-ruler">
    <x-admins.sidebar-item :name="__('lang.e_service_table')" icon="fas fa-list" :activeRoutes="['admin.services*']"
        route="services.index" />

    <x-admins.sidebar-item :name="__('lang.e_service_review_plural')" icon="fas fa-comments"
        :activeRoutes="['admin.service-reviews*']" route="service-reviews.index" />
</x-admins.sidebar-items>

{{-- BOOKINGS --}}
<x-admins.sidebar-items :name="__('lang.booking_plural')"
    :routes="[['admin.booking-statuses*', 'admin.bookings*'], 'menu-open']" icon="fas fa-calendar-check">
    {{--
    <x-admins.sidebar-item :name="__('lang.booking_status_plural')" icon="fas fa-server"
        :activeRoutes="['admin.booking-statuses*']" route="booking-statuses.index" /> --}}

    <x-admins.sidebar-item :name="__('lang.booking_plural')" icon="fas fa-calendar-check"
        :activeRoutes="['admin.bookings*']" route="bookings.index" />
</x-admins.sidebar-items>

{{-- COUPONS --}}
<x-admins.sidebar-item :name="__('lang.coupon_plural')" icon="fas fa-ticket-alt" :activeRoutes="['admin.coupons*']"
    route="coupons.index" />

{{-- FAQS --}}
<x-admins.sidebar-items :name="__('lang.faq_plural')" :routes="[['admin.faqs*', 'admin.faq-categories*'], 'menu-open']"
    icon="fas fa-question-circle">
    <x-admins.sidebar-item :name="__('lang.faq_plural')" icon="fas fa-life-ring" :activeRoutes="['admin.faqs*']"
        route="faqs.index" />

    <x-admins.sidebar-item :name="__('lang.faq_category_plural')" icon="fas fa-folder-open"
        :activeRoutes="['admin.faq-categories*']" route="faq-categories.index" />
</x-admins.sidebar-items>

<li class="nav-header">{{trans('lang.payment_plural')}}</li>

{{-- PAYMENTS --}}
<x-admins.sidebar-items :name="__('lang.payment_plural')"
    :routes="[['admin.payment-statuses*', 'admin.payment-methods*','admin.provider-payouts*', 'admin.payments*'], 'menu-open']"
    icon="fas fa-money-check-alt">
    <x-admins.sidebar-item :name="__('lang.payment_plural')" icon="fas fa-money-check-alt"
        :activeRoutes="['admin.payments*']" route="payments.index" />

    <x-admins.sidebar-item :name="__('lang.payment_status_plural')" icon="fas fa-file-invoice-dollar"
        :activeRoutes="['admin.payment-statuses*']" route="payment-statuses.index" />
    {{--
    <x-admins.sidebar-item :name="__('lang.payment_method_plural')" icon="fas fa-credit-card"
        :activeRoutes="['admin.payment-methods*']" route="payment-methods.index" />

    <x-admins.sidebar-item :name="__('lang.e_provider_payout_plural')" icon="fas fa-money-bill-wave"
        :activeRoutes="['admin.provider-payouts*']" route="provider-payouts.index" /> --}}
</x-admins.sidebar-items>

<li class="nav-header">{{trans('lang.app_setting')}}</li>

{{-- SETTINGS --}}
<x-admins.sidebar-items :name="__('lang.app_setting')"
    :routes="[['admin.currencies*', 'admin.taxes*', 'admin.terms*','admin.partners*','admin.settings*', 'admin.users*', 'admin.mails*', 'admin.social-auth*', 'admin.setting-payment*', 'admin.slides*', 'admin.localisation*'], 'menu-open']"
    icon="fas fa-cogs">
    <x-admins.sidebar-item :name="__('lang.app_setting_globals')" icon="fas fa-cog" :activeRoutes="['admin.settings*']"
        route="settings.index" />

    <x-admins.sidebar-item :name="__('lang.currency_plural')" icon="fas fa-dollar-sign"
        :activeRoutes="['admin.currencies*']" route="currencies.index" />

    <x-admins.sidebar-item :name="__('lang.user_plural')" icon="fas fa-users" :activeRoutes="['admin.users*']"
        route="users.index" />

    <x-admins.sidebar-item :name="__('lang.testimonial_plural')" icon="fas fa-star"
        :activeRoutes="['admin.testimonials*']" route="testimonials.index" />

    <x-admins.sidebar-item :name="__('lang.terms')" icon="fas fa-file-contract" :activeRoutes="['admin.terms*']"
        route="terms.index" />

    <x-admins.sidebar-item :name="__('lang.app_setting_mail')" icon="fas fa-envelope" :activeRoutes="['admin.mails*']"
        route="mails.smtp" />

    <x-admins.sidebar-item :name="__('lang.app_setting_social')" icon="fas fa-globe"
        :activeRoutes="['admin.social-auth*']" route="social-auth.index" />

    <x-admins.sidebar-item :name="__('lang.app_setting_payment')" icon="fas fa-credit-card"
        :activeRoutes="['admin.setting-payment*']" route="setting-payment.index" />

    <x-admins.sidebar-item :name="__('lang.slide_plural')" icon="fas fa-images" :activeRoutes="['admin.slides*']"
        route="slides.index" />

    <x-admins.sidebar-item :name="__('lang.tax_plural')" icon="fas fa-coins" :activeRoutes="['admin.taxes*']"
        route="taxes.index" />

    <x-admins.sidebar-item :name="__('lang.app_setting_localisation')" icon="fas fa-language"
        :activeRoutes="['admin.localisation*']" route="localisation.index" />

    <x-admins.sidebar-item :name="__('lang.partner')" icon="fas fa-image" :activeRoutes="['admin.partners*']"
        route="partners.index" />
</x-admins.sidebar-items>
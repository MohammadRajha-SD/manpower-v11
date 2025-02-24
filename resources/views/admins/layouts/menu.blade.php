<x-admins.sidebar-item
 :name="__('lang.dashboard')" 
 icon="fas fa-tachometer-alt" 
 :activeRoutes="['admin.dashboard']"
 route="dashboard"
/>

<li class="nav-header">{{trans('lang.app_management')}}</li>

<x-admins.sidebar-item
 :name="__('lang.category_plural')" 
 icon="fas fa-folder-open" 
 :activeRoutes="['admin.categories*']"
 route="categories.index"
/>

<x-admins.sidebar-item
 :name="__('lang.pack')" 
 icon="fas fa-folder-open" 
 :activeRoutes="['admin.packs*']"
 route="packs.index"
/>

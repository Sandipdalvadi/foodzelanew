@php
   $siteSettings = App\Models\SiteSettings::first();   
   $siteSettings = !empty($siteSettings) ? $siteSettings : new SiteSettings;
   $manageView = [];
   if(auth()->user()->permissions != ''){
      $userPermission = explode(",",auth()->user()->permissions);
      $manageView = App\Models\Permissions::whereIn('id', $userPermission)->get();
   }   
   $viewArray = [];
   foreach($manageView as $view){
      $viewArray[] = $view->full_url;
   }
@endphp
<div class="sidebar-wrapper">
    <div class="logo-wrapper">
       <a href="{{route('admin.home')}}"><img class="img-fluid for-light" src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}" alt="" /><img class="img-fluid for-dark" src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}" alt="" /></a>
       <div class="back-btn"><i class="fa fa-angle-left"></i></div>
       <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
    </div>
    <div class="logo-icon-wrapper">
       <a href="{{route('admin.home')}}"><img class="img-fluid" src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}" alt="" /></a>
    </div>
    <nav class="sidebar-main">
       <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
       <div id="sidebar-menu">
          <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="{{route('admin.home')}}"><img class="img-fluid" src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}" alt="" /></a>
               <div class="mobile-back text-right"><span>{{ __('message.back') }}</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            @if(auth()->user()->role == 1 || in_array('/admin/home',$viewArray))
             <li class="sidebar-list">
                <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.home')}}"><i data-feather="home"></i><span>{{ __('message.dashboard') }} </span>
                </a> 
             </li>
             @endif
             @if(auth()->user()->role == 1 || in_array('/admin/categories/index',$viewArray))
             <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.categories.index') }}"><i data-feather="grid"></i><span>{{ __('message.categories') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/foods/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="javascript:void(0)"><i data-feather="food"></i><span>{{ __('message.foods') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/orders/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="javascript:void(0)"><i data-feather="file-text"></i><span>{{ __('message.orders') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/managers/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.managers.index')}}"><i data-feather="user"></i><span>{{ __('message.managers') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/restaurent_owners/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.restaurent_owners.index') }}"><i data-feather="shop-owner"></i><span>{{ __('message.restaurent_owners') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/restaurents/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.restaurents.index') }}"><i data-feather="restaurents"></i><span>{{ __('message.restaurents') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/drivers/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.drivers.index') }}"><i data-feather="taxi-driver"></i><span>{{ __('message.drivers') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/users/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.users.index') }}"><i data-feather="user"></i><span>{{ __('message.users') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/sitesetting/index',$viewArray))
             <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.sitesetting.index')}}"><i data-feather="settings"></i><span>{{ __('message.sitesetting') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/terms_conditions/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.terms_conditions.index')}}"><i data-feather="clipboard"></i><span>{{ __('message.terms_conditions') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/about_us/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.about_us.index')}}"><i data-feather="users"></i><span>{{ __('message.about_us') }} </span>
               </a> 
            </li>
            @endif
            @if(auth()->user()->role == 1 || in_array('/admin/permissions/index',$viewArray))
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.permissions.index')}}"><i data-feather="users"></i><span>{{ __('message.permissions') }} </span>
               </a> 
            </li>
            @endif
            </ul>
       </div>
       <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
 </div>
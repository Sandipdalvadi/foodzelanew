<div class="loader-wrapper">
    <div class="loader-box">
        <div class="loader-7"></div>
    </div>
</div>
<div class="page-header">
    <div class="header-wrapper row m-0">
        <form class="form-inline search-full" action="#" method="get">
            <div class="form-group w-100">
                <div class="Typeahead Typeahead--twitterUsers">
                    <div class="u-posRelative">
                        <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                            placeholder="Search Cuba .." name="q" title="" autofocus />
                        <div class="spinner-border Typeahead-spinner" role="status"><span
                                class="sr-only">{{ __('message.loading') }}</span></div>
                        <i class="close-search" data-feather="x"></i>
                    </div>
                    <div class="Typeahead-menu"></div>
                </div>
            </div>
        </form>
        @php
            $siteSettings = App\Models\SiteSettings::first();   
            $siteSettings = !empty($siteSettings) ? $siteSettings : new SiteSettings;
        @endphp
        
        <div class="header-logo-wrapper">
            <div class="logo-wrapper">
                <a href="{{route('admin.home')}}"><img class="img-fluid"
                        src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}" alt="" /></a>
            </div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="sliders"></i></div>
        </div>
        <div class="left-header col horizontal-wrapper pl-0">

        </div>
        <div class="nav-right col-8 pull-right right-header p-0">
            <ul class="nav-menus">
                <li class="language-nav">
                    <div class="translate_wrapper">
                        <div class="current_lang">
                            <div class="lang"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">en </span>
                            </div>
                        </div>
                        <div class="more_lang">
                            <a href="{{URL::To('admin/locale/en')}}" class="{{ app()->getLocale() == 'en' ? 'active' : ''}}">
                                <div class="lang selected" data-value="en"><i class="flag-icon flag-icon-us"></i> <span
                                        class="lang-txt">English</span><span> (US)</span></div>
                            </a>
                            <a href="{{URL::To('admin/locale/ar')}}" class="{{ app()->getLocale() == 'ar' ? 'active' : ''}}">
                                <div class="lang " data-value="de"><i class="flag-icon flag-icon-de"></i> <span
                                        class="lang-txt">Arabic</span></div>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="onhover-dropdown">
                    <div class="notification-box"><i data-feather="bell"> </i><span
                            class="badge badge-pill badge-secondary">4 </span></div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <i data-feather="bell"></i>
                            <h6 class="f-18 mb-0">{{ __('message.notitications') }}</h6>
                        </li>
                        <li>
                            <p><i class="fa fa-circle-o mr-3 font-primary"> </i>Delivery processing <span
                                    class="pull-right">10 min.</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle-o mr-3 font-success"></i>Order Complete<span class="pull-right">1
                                    hr</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle-o mr-3 font-info"></i>Tickets Generated<span class="pull-right">3
                                    hr</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle-o mr-3 font-danger"></i>Delivery Complete<span
                                    class="pull-right">6 hr</span></p>
                        </li>
                        <li><a class="btn btn-primary" href="#">Check all notification</a></li>
                    </ul>
                </li>
                <li>
                    <div class="mode"><i class="fa fa-moon-o"></i></div>
                </li>
                <li class="maximize">
                    <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a>
                </li>
                <li class="profile-nav onhover-dropdown p-0 mr-0">
                    <div class="media profile-media">
                        <img class="b-r-10" src="{{Auth::user()->profile_pic ? file_exists_in_folder('profile_pic', Auth::user()->profile_pic) : file_exists_in_folder('profile_pic', '')}}"
                            alt="" />
                        <div class="media-body">
                            <span>{{ Auth::user()->name }}</span>
                            <p class="mb-0 font-roboto">{{ Auth::user()->name }} <i class="middle fa fa-angle-down"></i></p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <a href="{{route('admin.profile.index')}}"><i data-feather="user"></i><span>{{ __('message.edit_profile') }} </span></a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i data-feather="log-out"> </i>
                                <span>{{ __('message.logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            {{-- <a href="#"><i data-feather="log-in"> </i><span>Log
                                    out</span></a> --}}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">
        <div class="ProfileCard-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0">
                <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                <polygon points="12 15 17 21 7 21 12 15"></polygon>
            </svg>
        </div>
        <div class="ProfileCard-details">
        <div class="ProfileCard-realName">{{ Auth::user()->name }}</div>
        </div>
        </div>
      </script>
        <script class="empty-template" type="text/x-handlebars-template">
            <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
      </script>
    </div>
</div>

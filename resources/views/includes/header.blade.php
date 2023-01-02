<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            <!-- sidebar-toggle-->
            <a class="logo-horizontal " href="/dashboard">
                <img src="{{asset(env('APP_LOGO'))}}" class="header-brand-img desktop-logo" alt="logo1h">
                <img src="{{asset(env('APP_LOGO'))}}" class="header-brand-img light-logo1" alt="logo2h" style="height:auto !important; width:100px !important;">
            </a>
            <!-- LOGO -->

            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                <div class="dropdown d-none">
                    <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                        <i class="fe fe-search"></i>
                    </a>
                    <div class="dropdown-menu header-search dropdown-menu-start">
                        <div class="input-group w-100 p-2">
                            <input type="text" class="form-control" placeholder="Search....">
                            <div class="input-group-text btn btn-primary">
                                <i class="fe fe-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SEARCH -->
                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                </button>
                <div class="navbar navbar-collapse responsive-navbar p-0">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        <div class="d-flex order-lg-2">
                            <div class="dropdown d-lg-none d-flex">
                                <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                    <i class="fe fe-search"></i>
                                </a>
                                <div class="dropdown-menu header-search dropdown-menu-start">
                                    <div class="input-group w-100 p-2">
                                        <input type="text" class="form-control" placeholder="Search....">
                                        <div class="input-group-text btn btn-primary">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- COUNTRY -->
                            <!-- <div class="d-flex country">
                                <a class="nav-link icon text-center" href="/chat/box">
                                    <i class="fe fe-mail"></i><span class="fs-16 ms-2 d-none d-xl-block"></span>
                                </a>
                            </div> -->

                         <!-- Message chat -->
                            <div class="dropdown  d-flex message">
                                <a class="nav-link icon text-center" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fe fe-mail"></i><span class="pulse-danger unread" style="display: none;"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <div class="d-flex message-head">

                                        </div>
                                    </div>
                                    <div class="message-menu message-menu-scroll ps">
                                        <div class="messages-body">

                                        </div>
                                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a href="/message/compose" class="dropdown-item text-center p-3 text-muted">See all
                                        Messages</a>
                                </div>
                            </div>
                            <!-- Message chat -->

                              <!-- Video chat -->
                              <div class="dropdown  d-flex video_message">
                                <a class="nav-link icon text-center" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fe fe-video"></i><span class="pulse-danger video_unread" style="display: none;"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <div class="d-flex video_message-head">

                                        </div>
                                    </div>
                                    <div class="message-menu message-menu-scroll ps">
                                        <div class="video_messages-body">

                                        </div>
                                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a href="/video/chat/compose" class="dropdown-item text-center p-3 text-muted">Go to Chat</a>
                                </div>
                            </div>
                            <!-- Video chat -->
                            <!-- SEARCH -->
                            <div class="dropdown  d-flex">
                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                    <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                    <span class="light-layout"><i class="fe fe-sun"></i></span>
                                </a>
                            </div>
                            <!-- Theme-Layout -->
                            <div class="dropdown d-flex">
                                <a class="nav-link icon full-screen-link nav-link-bg">
                                    <i class="fe fe-minimize fullscreen-button"></i>
                                </a>
                            </div>
                            <!-- FULL-SCREEN -->

                            <!-- SIDE-MENU -->
                            <div class="dropdown d-flex profile-1">
                                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">

                                    @if(Auth::user()->image==NULL)
                                    <img src="{{asset('assets/images/users/21.jpg')}}" alt="profile-user" class="avatar  profile-user brround cover-image">

                                    @else
                                    <img src="{{asset('/uploads/files/'.Auth::user()->image)}}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                    @endif


                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        @if(Auth::user()->hasRole('Tenant'))
                                        <div class="text-start">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ucwords(Auth::user()->buisness_name)}}</h5>
                                        </div>
                                        @endif
                                        <div class="text-start">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ucwords(Auth::user()->first_name)}} {{Auth::user()->last_name}}</h5>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-item" href="{{ url('/profile')}}">
                                        <i class="dropdown-icon fe fe-user"></i> Profile
                                    </a>
                                    <form method="post" action="{{route('logout')}}">

                                        @csrf

                                        <button class="dropdown-item" type="submit" style="padding-left: 0 !important;margin-left: 23px;padding-top:10px;">
                                            <i class="side-menu__icon fe fe-log-out" style="padding-right:6px;"></i>
                                            <span class="text-dark">{{ __('Log out') }}</span>

                                        </button>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
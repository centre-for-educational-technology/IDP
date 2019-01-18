<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ELU - Tere tulemast</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('favicons/favicon-32x32.png')}}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{asset('favicons/favicon-16x16.png')}}" sizes="16x16">
    <link rel="manifest" href="{{asset('favicons/manifest.json')}}">
    <link rel="mask-icon" href="{{asset('favicons/safari-pinned-tab.svg')}}" color="#ff4385">
    <link rel="shortcut icon" href="{{asset('favicons/favicon.ico')}}">
    <meta name="msapplication-config" content="{{asset('favicons/browserconfig.xml')}}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.0/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!--
    <link href="{{ url(elixir('css/app.css')) }}" rel="stylesheet">
    <link href="{{ url(asset('/css/styles.css')) }}" rel="stylesheet">
    -->
    <link href="{{ url(asset(elixir('css/uni_style_common.css'))) }}" rel="stylesheet">
    <link href="{{ url(asset(elixir('css/uni_style_welcome.css'))) }}" rel="stylesheet">

    <style>
        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <script src="{{ url(asset('/js/vendor.js')) }}"></script>
    
    <!-- HEADER -->
    <div class="header-container">

        <div class="header-navbar">
            <div class="row">
                <nav class="col-6 navbar navbar-expand-sm navbar-left bg-light navbar-light navbar-header navbar-left-padding">

                    <div>
                        @if (!Auth::guest())
                            <span class="navbar-name">{{ Auth::user()->name }}</span>

                            @if (Auth::user()->is('oppejoud'))
                                <span class="navbar-role">{{ trans('nav.oppejoud') }}</span>
                            @endif

                            @if (Auth::user()->is('student'))
                                <span class="navbar-role">{{ trans('nav.student') }}</span>
                            @endif

                            @if (Auth::user()->is('admin'))
                                <span class="navbar-role">{{ trans('nav.admin') }}</span>
                            @endif

                            @if (Auth::user()->is('superadmin'))
                                <span class="navbar-role">{{ trans('nav.superadmin') }}</span>
                            @endif

                            @if (Auth::user()->is('project_moderator'))
                                <span class="navbar-role">{{ trans('nav.project_moderator') }}</span>
                            @endif
                        @endif
                    </div>
                </nav>
                <nav class="col-6 navbar navbar-expand-sm navbar-right bg-light navbar-light navbar-header">
                    <div class="sm-link"><a href="#"><img src="{{ url(asset('/css/youtube.svg')) }}" alt="youtube"></a></div>
                    <div class="sm-link"><a href="#"><img src="{{ url(asset('/css/facebook.svg')) }}" alt="facebook"></a></div>
                    @if (Auth::guest())
                        <div><a href="{{ url('/login/choose') }}"><button class="btn-login">{{ trans('nav.login') }}</button></a></div>
                    @else
                        <div><a href="{{ url('/logout') }}"><button class="btn-login">{{ trans('nav.logout') }}</button></a></div>
                        <div><a href="{{ url('profile') }}"><button class="btn-login">{{ trans('nav.profile') }}</button></a></div>
                    @endif
                    @if (App::getLocale() == 'en')
                        <span class="navbar-text">
                            <a href="{{ route('lang.switch', 'et') }}" label="choose language ET">eesti</a>
                        </span>
                    @elseif(App::getLocale() == 'et')
                        <span class="navbar-text">
                            <a href="{{ route('lang.switch', 'en') }}" label="choose language EN">english</a>
                        </span>
                    @endif
                    
                </nav>
            </div>
        </div>
    
        <!-- MAIN MENU -->
        <div class="menu-positioning">
            <nav class="navbar navbar-expand-lg bg-light navbar-light">
                
                <!-- Logo -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url(asset('/css/TLY_ELU.svg')) }}" alt="TLÜ ELU">
                </a>
                
                <!-- Toggler/collapsibe Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Links -->
                <div class="collapse navbar-collapse navbar-right" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/projects/open') }}">{{ trans('front.search') }}</a>
                        </li>
                        @if (Auth::guest())
                        @elseif (!Auth::guest())
                            <li class="nav-item">
                                <a class="nav-link" href="https://docs.google.com/document/d/1h8wX0TjFTFCnZPlXj0gccZUoLk8TGc9iWv_AEZHBkWI/edit" target="_blank">{{ trans('front.seminaries') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://drive.google.com/drive/folders/0BxOqwuSVpflsMlBfR2FiZm93ZE0" target="_blank">{{ trans('front.materials') }}</a>
                            </li>
                            <!--
                            <li class="nav-item">
                                <a class="nav-link" href="#">ETTEVÕTTELE</a>
                            </li>
                            -->
                            @if (Auth::user()->is('admin'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/admin/all-projects') }}">Admin paneel</a>
                                </li>
                            @endif
                            @if (Auth::user()->is('superadmin'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/superadmin/log') }}">Superadmin paneel</a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="https://docs.google.com/document/d/1tuLxJ3KL27HcS7JmfdxuZD05djkEaoPHkHBlSinwEZg/edit" target="_blank">{{ trans('front.academic_calendar') }}</a>
                        </li>
                        <li {{ setActive('faq') }} class="nav-item">
                            <a class="nav-link" href="{{ url('/faq') }}">{{ trans('front.faq') }}</a>
                        </li>
                    </ul>
                </div>
                
            </nav>
        </div>
    
    </div>
  
    
    <div class="container">

        <!-- CTA BUTTONS -->
        <div class="row cta-row">     
            <div class=" col-lg-4">
                <div class="main-cta-block">
                    <a href="{{ url('/faq') }}">
                        <div class="pad">
                            <p class="cta-1">ELU</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class=" col-lg-4">
                <div class="main-cta-block">
                    <a href="{{ url('/projects/open') }}">
                        <div class="pad">
                            <p class="cta-2">
                                <img src="{{ url(asset('/css/projects.svg')) }}" class="big-icons" alt="search" height="110vw">
                            </p>
                            <p class="cta-2">{{ trans('front.all_projects') }}</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class=" col-lg-4">
                <div class="main-cta-block">
                    @if (Auth::guest())
                    <a href="{{ url('/login/choose') }}">
                    @else
                        @if (Auth::user()->is('student') && !Auth::user()->is('oppejoud'))
                        <a href="{{ url('/student/project/new') }}">
                        @elseif(Auth::user()->is('oppejoud'))
                        <a href="{{ url('/project/new') }}">
                        @elseif(Auth::user()->is('admin'))
                        <a href="{{ url('/project/new') }}">
                        @else
                        <a href="{{ url('/login/choose') }}">
                        @endif
                    @endif
                        <div class="pad">
                            <p class="cta-2">
                                <img src="{{ url(asset('/css/idea.svg')) }}" class="big-icons" alt="brain" height="120vw">
                            </p>
                            <p class="cta-2">{{ trans('front.i_have_idea') }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
      
        <!-- NEWS -->
        <div class="row">     
            <div class=" col-lg-4">
                <div class="news-block" style="overflow:auto;">
                    <div class="news">
                        @if (App::getLocale() == 'et')
                            @if(!empty($news->body_et))
                            {!! $news->body_et !!}
                            @endif
                        @elseif(App::getLocale() == 'en')
                            @if(!empty($news->body_en))
                            {!! $news->body_en !!}
                            @endif
                        @endif
                        <!--
                        <div class="news-link"><a href="#">{{trans('front.news')}}</a></div>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>

<link rel="shortcut icon" href="{{ asset ('assets/logotab.p')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset ('assets/fe/assets/fonts/unicons/unicons.css')}}">
  <link rel="stylesheet" href="{{ asset ('assets/fe/assets/css/plugins.css')}}">
  <link rel="stylesheet" href="{{ asset ('assets/fe/style.css') }}">
  <link rel="stylesheet" href="{{ asset ('assets/fe/assets/css/colors/purple.css')}}">
  <link rel="preload" href="{{ asset ('assets/fe/assets/css/fonts/urbanist.css')}}" as="style" onload="this.rel='stylesheet'">
<link rel="stylesheet" href="{{ asset('assets/fe/assets/fonts/unicons/unicons.css') }}">
  <style>
    {{--Marquee--}}
     .logo-marquee { overflow: hidden; width: 100%; }
      .logo-track { display: flex; width: max-content; gap: 1.5rem; align-items: center;
                    --speed: 14s; animation: marquee-right var(--speed) linear infinite; }
      .logo-set { display: flex; gap: 1.5rem; align-items: center; }
      .logo-item img { height: 48px; object-fit: contain; display: block; }
      @keyframes marquee-right {
        from { transform: translateX(-50%); }
        to   { transform: translateX(0%); }
      }
    {{--endMarquee--}}
    .language-select .nav-link{
      color: #ffffff;
    }
    .language-select .nav-link:hover{
      color: #ffffffb3;
    }
    .navbar-light.fixed.navbar-stick .language-select .nav-link{
      color: #343f52;
    }
    .navbar-light.fixed.navbar-stick .language-select .nav-link:hover,
    .navbar-light.fixed.navbar-stick .language-select .nav-link:after,
    .navbar-light.fixed.navbar-stick .nav-link:hover{
              color: #747ed1;
    }
      @media (min-width: 992px){
      .navbar-expand-lg.navbar-light .dropdown:not(.dropdown-submenu)>.dropdown-toggle:after {
          color: #747ed1;
      }
      }
      @media (max-width: 991.98px){
      .navbar-expand-lg .navbar-collapse .dropdown-toggle:after {
        color: #ffffff !important;
      }
  }
  </style>

@stack('styles')

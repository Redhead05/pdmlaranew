<link rel="shortcut icon" href="{{ asset ('assets/logotab.png')}}">
<link rel="stylesheet" type="text/css" href="{{ asset ('assets/fe/assets/fonts/unicons/unicons.css')}}">
<link rel="stylesheet" href="{{ asset ('assets/fe/assets/css/plugins.css')}}">
<link rel="stylesheet" href="{{ asset ('assets/fe/style.css') }}">
<link rel="stylesheet" href="{{ asset ('assets/fe/assets/css/colors/purple.css')}}">
<link rel="preload" href="{{ asset ('assets/fe/assets/css/fonts/urbanist.css')}}" as="style" onload="this.rel='stylesheet'">
<link rel="stylesheet" href="{{ asset('assets/fe/assets/fonts/unicons/unicons.css') }}">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
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
<style>
    #journal-scroll::-webkit-scrollbar {
        width: 6px;
        cursor: pointer;
    }

    #journal-scroll::-webkit-scrollbar-track {
        background-color: rgba(229, 231, 235 var(--bg-opacity));
        cursor: pointer;
    }

    #journal-scroll::-webkit-scrollbar-thumb {
        cursor: pointer;
        background-color: #a0aec0;
    }
</style>
{{--start chatbot--}}
<style>
    /* Chatbot Styles */
    .chatbot__button {
        position: fixed;
        bottom: 35px;
        right: 110px;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #605dba;
        color: #f3f7f8;
        border: none;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(96, 93, 186, 0.4);
        z-index: 9999;
        pointer-events: auto;
        transition: all 0.3s ease;
    }

    .chatbot__button:hover {
        background: #4e4a9c;
        transform: scale(1.1);
        box-shadow: 0 15px 35px rgba(96, 93, 186, 0.6);
    }

    .chatbot__button span {
        position: absolute;
        font-size: 28px;
    }

    .show-chatbot .chatbot__button span:first-child,
    .chatbot__button span:last-child {
        opacity: 0;
    }

    .show-chatbot .chatbot__button span:last-child {
        opacity: 1;
    }

    .chatbot {
        position: fixed;
        bottom: 110px;
        right: 110px;
        width: 350px;
        height: 300px;
        background-color: #f3f7f8;
        border-radius: 15px;
        box-shadow: 0 0 128px 0 rgba(0, 0, 0, 0.1), 0 32px 64px -48px rgba(0, 0, 0, 0.5);
        transform: scale(0.5);
        transition: transform 0.3s ease;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
        z-index: 9999;
    }

    /* Open widget */
    .chatbot.open {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1);
    }

    /* Chat Interface Styles */
    .chatbot__box {
        overflow-y: auto;
        scrollbar-width: thin; /* Firefox */
        scrollbar-color: rgba(34, 211, 238, 0.8) rgba(15, 23, 42, 0.35);
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chatbot__box {
        padding: 10px 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chatbot__chat {
        width: 100%;
        display: flex;
        align-items: flex-end;
    }

    .chatbot__chat.incoming {
        justify-content: flex-start;
        gap: 8px;
    }

    .chatbot__chat.outgoing {
        justify-content: flex-end;
    }

    .chatbot__chat.incoming span.material-symbols-outlined {
        width: 24px;
        height: 24px;
        line-height: 24px;
        border-radius: 10px;
        font-size: 16px;
    }

    .chatbot__chat p {
        margin: 0;
        max-width: 78%;
        padding: 8px 10px;
        font-size: 12px;
        line-height: 1.25;
        letter-spacing: 0.1px;
    }

    .chatbot__chat.outgoing p {
        max-width: 82%;
    }

    /* Smooth scroll + thin scrollbar */
    .chatbot__box {
        scroll-behavior: smooth;
    }

    .chatbot__box::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot__box::-webkit-scrollbar-track {
        background: rgba(2, 6, 23, 0.25);
        border-radius: 999px;
    }

    .chatbot__box::-webkit-scrollbar-thumb {
        background: rgba(34, 211, 238, 0.65);
        border-radius: 999px;
    }

    .chatbot__box::-webkit-scrollbar-thumb:hover {
        background: rgba(34, 211, 238, 0.95);
    }

    @media (max-width: 490px) {
        .chatbot {
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border-radius: 0;
        }
        .chatbot__button {
            right: 20px;
            bottom: 20px;
        }
    }

    /* Compact form controls so login fits in 300px height */
    .chatbot #chatbot-login input {
        height: 26px;
    }

    .chatbot #chatbot-login label {
        display: block;
        font-size: 10px;
        line-height: 1.15;
        margin-bottom: 2px;
    }

    .chatbot #chatbot-login .chatbot__form-group {
        margin-bottom: 6px;
    }

    .chatbot__chat-interface {
        height: 100%;
    }

    .chatbot__chat-interface .chatbot__box {
        padding-bottom: 8px;
    }

    /* Improve spacing & wrapping */
    .chatbot__chat.incoming p,
    .chatbot__chat.outgoing p {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

</style>
{{--end chatbot--}}
@stack('styles')

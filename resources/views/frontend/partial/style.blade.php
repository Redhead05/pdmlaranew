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
        width: 60px;
        height: 60px;
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
        z-index: 1009;
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
        width: 420px;
        background-color: #f3f7f8;
        border-radius: 15px;
        box-shadow: 0 0 128px 0 rgba(0, 0, 0, 0.1), 0 32px 64px -48px rgba(0, 0, 0, 0.5);
        transform: scale(0.5);
        transition: transform 0.3s ease;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
        z-index: 1009;
    }

    .show-chatbot .chatbot {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1);
    }

    .chatbot__header {
        position: relative;
        background: linear-gradient(135deg, #605dba 0%, #4e4a9c 100%);
        text-align: center;
        padding: 16px 20px;
    }

    .chatbot__header-actions {
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .chatbot__logout {
        font-size: 0.85rem;
        color: #f3f7f8;
        cursor: pointer;
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        transition: background 0.3s ease;
    }

    .chatbot__logout:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .chatbot__close {
        display: none;
        color: #f3f7f8;
        cursor: pointer;
        font-size: 24px;
    }

    .chatbox__title {
        font-size: 1.4rem;
        color: #f3f7f8;
        margin: 0;
        font-weight: 600;
    }

    /* Login Form Styles */
    .chatbot__login-content {
        padding: 30px 25px;
        text-align: center;
    }

    .chatbot__login-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #605dba 0%, #4e4a9c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(96, 93, 186, 0.3);
    }

    .chatbot__login-icon span {
        font-size: 40px;
        color: #f3f7f8;
    }

    .chatbot__login-content h4 {
        font-size: 1.3rem;
        color: #202020;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .chatbot__login-content p {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 25px;
    }

    .chatbot__form-group {
        margin-bottom: 18px;
        text-align: left;
    }

    .chatbot__form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        color: #202020;
        margin-bottom: 6px;
    }

    .chatbot__form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.3s ease;
        outline: none;
    }

    .chatbot__form-group input:focus {
        border-color: #605dba;
    }

    .chatbot__submit-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #605dba 0%, #4e4a9c 100%);
        color: #f3f7f8;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .chatbot__submit-btn:hover {
        background: linear-gradient(135deg, #4e4a9c 0%, #3d3880 100%);
        box-shadow: 0 5px 15px rgba(96, 93, 186, 0.4);
    }

    /* Chat Interface Styles */
    .chatbot__box {
        height: 510px;
        overflow-y: auto;
        padding: 30px 20px 100px;
        list-style: none;
    }

    .chatbot__box::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot__box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .chatbot__box::-webkit-scrollbar-thumb {
        background: #605dba;
        border-radius: 10px;
    }

    .chatbot__box::-webkit-scrollbar-thumb:hover {
        background: #4e4a9c;
    }

    .chatbot__chat {
        display: flex;
        margin-bottom: 20px;
    }

    .chatbot__chat p {
        max-width: 75%;
        font-size: 0.95rem;
        white-space: pre-wrap;
        color: #f3f7f8;
        background: linear-gradient(135deg, #605dba 0%, #4e4a9c 100%);
        border-radius: 10px 10px 0 10px;
        padding: 12px 16px;
        word-wrap: break-word;
    }

    .chatbot__chat p.error {
        color: #721c24;
        background: #f8d7da;
    }

    .incoming p {
        color: #202020;
        background: #e0e0e0;
        border-radius: 10px 10px 10px 0;
    }

    .incoming span {
        width: 36px;
        height: 36px;
        line-height: 36px;
        color: #f3f7f8;
        background: linear-gradient(135deg, #605dba 0%, #4e4a9c 100%);
        border-radius: 8px;
        text-align: center;
        align-self: flex-end;
        margin: 0 10px 7px 0;
        flex-shrink: 0;
    }

    .outgoing {
        justify-content: flex-end;
    }

    .chatbot__input-box {
        position: absolute;
        bottom: 0;
        width: 100%;
        display: flex;
        gap: 8px;
        align-items: center;
        border-top: 2px solid #605dba;
        background: #f3f7f8;
        padding: 8px 20px;
    }

    .chatbot__textarea {
        width: 100%;
        min-height: 55px;
        max-height: 180px;
        font-size: 0.95rem;
        padding: 16px 15px 16px 0;
        color: #202020;
        border: none;
        outline: none;
        resize: none;
        background: transparent;
        font-family: 'Poppins', sans-serif;
    }

    .chatbot__textarea::placeholder {
        font-family: 'Poppins', sans-serif;
        color: #999;
    }

    .chatbot__input-box span {
        font-size: 1.75rem;
        color: #605dba;
        cursor: pointer;
        visibility: hidden;
    }

    .chatbot__textarea:valid ~ span {
        visibility: visible;
    }

    @media (max-width: 490px) {
        .chatbot {
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border-radius: 0;
        }
        .chatbot__box {
            height: 90%;
        }
        .chatbot__close {
            display: inline;
        }
        .chatbot__button {
            right: 20px;
            bottom: 20px;
        }
    }
</style>
{{--end chatbot--}}
@stack('styles')

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
    /* ===== Chatbot — on-brand light widget ===== */
    .chatbot__button {
        position: fixed;
        right: 24px;
        bottom: 88px;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #747ed1;
        color: #ffffff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0.5rem 1.5rem rgba(116, 126, 209, 0.45);
        z-index: 9999;
        transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .chatbot__button:hover {
        background: #5d64c4;
        transform: translateY(-2px);
        box-shadow: 0 0.75rem 1.75rem rgba(116, 126, 209, 0.55);
    }

    .chatbot__button:focus-visible {
        outline: 3px solid rgba(116, 126, 209, 0.35);
        outline-offset: 2px;
    }

    .chatbot__button span {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        line-height: 1;
        transition: opacity 0.18s ease, transform 0.18s ease;
    }

    .chatbot__button .chatbot__icon-open { opacity: 1; transform: scale(1); }
    .chatbot__button .chatbot__icon-close { opacity: 0; transform: scale(0.6); }
    .show-chatbot .chatbot__button .chatbot__icon-open { opacity: 0; transform: scale(0.6); }
    .show-chatbot .chatbot__button .chatbot__icon-close { opacity: 1; transform: scale(1); }

    .chatbot {
        position: fixed;
        right: 24px;
        bottom: 156px;
        width: 360px;
        max-width: calc(100vw - 24px);
        height: 560px;
        max-height: calc(100vh - 176px);
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 1rem 3rem rgba(30, 34, 40, 0.18);
        overflow: hidden;
        opacity: 0;
        transform: translateY(12px) scale(0.98);
        transform-origin: bottom right;
        pointer-events: none;
        transition: opacity 0.22s ease, transform 0.22s ease;
        z-index: 9999;
    }

    .chatbot.open {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }

    .chatbot__login,
    .chatbot__chat-interface {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .chatbot__header {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.85rem 1rem;
        background: linear-gradient(135deg, #747ed1, #5d64c4);
        color: #ffffff;
    }

    .chatbot__avatar {
        position: relative;
        flex: 0 0 auto;
    }

    .chatbot__avatar img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
        border: 2px solid rgba(255, 255, 255, 0.4);
    }

    .chatbot__avatar .chatbot__dot {
        position: absolute;
        right: -1px;
        bottom: -1px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #37D80A;
        border: 2px solid #747ed1;
    }

    .chatbot__title {
        flex: 1 1 auto;
        min-width: 0;
        line-height: 1.25;
    }

    .chatbot__title .chatbot__name {
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chatbot__title .chatbot__status {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .chatbot__action {
        flex: 0 0 auto;
        background: none;
        border: none;
        padding: 0.15rem;
        line-height: 1;
        color: rgba(255, 255, 255, 0.9);
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .chatbot__action:hover { color: #ffffff; }
    .chatbot__action:focus-visible { outline: 2px solid rgba(255, 255, 255, 0.9); outline-offset: 2px; border-radius: 4px; }
    .chatbot__action .uil { font-size: 18px; }

    .chatbot__logout {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .chatbot__logout:hover { color: #ffffff; text-decoration: underline; }

    /* Message list */
    .chatbot__box {
        flex: 1 1 auto;
        min-height: 0;
        list-style: none;
        margin: 0;
        padding: 1rem 0.9rem;
        overflow-y: auto;
        background: #f6f7f9;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        scroll-behavior: smooth;
    }

    .chatbot__box::-webkit-scrollbar { width: 6px; }
    .chatbot__box::-webkit-scrollbar-track { background: transparent; }
    .chatbot__box::-webkit-scrollbar-thumb { background: #d5d9e2; border-radius: 999px; }

    .chatbot__chat {
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
        max-width: 100%;
    }

    .chatbot__chat.incoming { justify-content: flex-start; }
    .chatbot__chat.outgoing { justify-content: flex-end; }

    .chatbot__msg-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
        flex: 0 0 auto;
    }

    .chatbot__bubble {
        max-width: 78%;
        padding: 0.55rem 0.8rem;
        font-size: 0.8rem;
        line-height: 1.45;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .chatbot__chat.incoming .chatbot__bubble {
        background: #ffffff;
        color: #343f52;
        border-radius: 4px 14px 14px 14px;
        box-shadow: 0 1px 2px rgba(30, 34, 40, 0.06);
    }

    .chatbot__chat.outgoing .chatbot__bubble {
        background: linear-gradient(135deg, #747ed1, #5d64c4);
        color: #ffffff;
        border-radius: 14px 4px 14px 14px;
    }

    /* Login view */
    .chatbot__login-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        background: #f6f7f9;
        padding: 1.2rem 1rem;
    }

    .chatbot__login-hint {
        font-size: 0.78rem;
        color: #60697b;
        margin: 0 0 0.9rem;
    }

    .chatbot__form .chatbot__field { margin-bottom: 0.7rem; }
    .chatbot__form label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        color: #343f52;
        margin-bottom: 0.25rem;
    }

    .chatbot__form input {
        width: 100%;
        height: 44px;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        color: #343f52;
        background: #ffffff;
        border: 1px solid #d5d9e2;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .chatbot__form input::placeholder { color: #959ca9; }
    .chatbot__form input:focus {
        border-color: #747ed1;
        box-shadow: 0 0 0 3px rgba(116, 126, 209, 0.15);
    }

    .chatbot__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 44px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #747ed1;
        border: none;
        border-radius: 50rem;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .chatbot__btn:hover { background: #5d64c4; }
    .chatbot__btn:active { transform: scale(0.98); }
    .chatbot__btn:disabled { opacity: 0.65; cursor: not-allowed; }
    .chatbot__btn:focus-visible { outline: 3px solid rgba(116, 126, 209, 0.35); outline-offset: 2px; }

    .chatbot__error {
        font-size: 0.75rem;
        color: #C43805;
        background: #FFE1DD;
        border-radius: 8px;
        padding: 0.45rem 0.7rem;
        margin-bottom: 0.7rem;
        display: none;
    }

    /* Input bar */
    .chatbot__inputbar {
        flex: 0 0 auto;
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
        padding: 0.7rem 0.9rem;
        background: #ffffff;
        border-top: 1px solid #eceef2;
    }

    .chatbot__textarea {
        flex: 1 1 auto;
        resize: none;
        max-height: 96px;
        border: 1px solid #d5d9e2;
        border-radius: 20px;
        padding: 0.55rem 0.9rem;
        font-size: 0.85rem;
        line-height: 1.35;
        color: #343f52;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .chatbot__textarea::placeholder { color: #959ca9; }
    .chatbot__textarea:focus {
        border-color: #747ed1;
        box-shadow: 0 0 0 3px rgba(116, 126, 209, 0.15);
    }

    .chatbot__send {
        flex: 0 0 auto;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #747ed1;
        color: #ffffff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .chatbot__send:hover { background: #5d64c4; }
    .chatbot__send:active { transform: scale(0.95); }
    .chatbot__send:disabled { opacity: 0.6; cursor: not-allowed; }
    .chatbot__send:focus-visible { outline: 3px solid rgba(116, 126, 209, 0.35); outline-offset: 2px; }
    .chatbot__send .uil { font-size: 18px; line-height: 1; }

    @media (max-width: 576px) {
        .chatbot {
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 100%;
            height: 100dvh;
            max-height: 100dvh;
            border-radius: 0;
        }
        .chatbot__button {
            right: 16px;
            bottom: 16px;
        }
    }
</style>
{{--end chatbot--}}
@stack('styles')

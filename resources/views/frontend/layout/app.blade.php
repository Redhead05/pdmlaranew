<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" href="{{ asset('assets/cobaheader.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="An impressive and flawless site template that includes various UI elements and countless features, attractive ready-made blocks and rich pages, basically everything you need to create a unique and professional website.">
    <meta name="keywords" content="Tailwind CSS, business, corporate, creative, gulp, marketing, minimal, modern, multipurpose, one page, responsive, saas, sass, seo, startup, html5 template, site template">
    <meta name="author" content="elemis">
    <title>BAN PDM JAWA TIMUR</title>
    @include('frontend.partial.style')
</head>

<body class="!font-Urbanist !text-[0.85rem]">
  <div class="page-frame" style="background-color:#fcf40a">

    <div class="grow shrink-0">
      <!-- /header -->
      @include('frontend.partial.header')
      <!-- /header -->
        <main class="pt-1 md:pt-1 lg:pt-1">
            @yield('content')
        </main>
      <!-- /section -->
    </div>
    <!-- /.content-wrapper -->
    @include('frontend.partial.footer')
  </div>

  <!-- Progress Wrapper -->
  <div class="progress-wrap fixed w-[2.3rem] h-[2.3rem] cursor-pointer block shadow-[inset_0_0_0_0.1rem_rgba(128,130,134,0.25)] z-[1010] opacity-0 invisible translate-y-3 transition-all duration-[0.2s] ease-[linear,margin-right] delay-[0s] rounded-[100%] right-6 bottom-6 motion-reduce:transition-none after:absolute after:content-['\e951'] after:text-center after:leading-[2.3rem] after:text-[1.2rem] after:!text-[#605dba] after:h-[2.3rem] after:w-[2.3rem] after:cursor-pointer after:block after:z-[1] after:transition-all after:duration-[0.2s] after:ease-linear after:left-0 after:top-0 motion-reduce:after:transition-none after:font-Unicons">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
          <path class="fill-none stroke-[#605dba] stroke-[4] box-border transition-all duration-[0.2s] ease-linear motion-reduce:transition-none" d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
      </svg>
  </div>

  <!-- Chatbot -->
  <div class="chatbot" aria-live="polite">
      <!-- LOGIN VIEW -->
      <div class="chatbot__login" id="chatbot-login">
          <div class="chatbot__header">
              <div class="chatbot__avatar">
                  <img src="{{ asset('assets/logotab.png') }}" alt="Admin BAN PDM JATIM" />
                  <span class="chatbot__dot"></span>
              </div>
              <div class="chatbot__title">
                  <div class="chatbot__name">Admin BAN PDM JATIM</div>
                  <div class="chatbot__status">Online</div>
              </div>
              <button type="button" class="chatbot__action chatbot__close" aria-label="Tutup chat">
                  <i class="uil uil-multiply"></i>
              </button>
          </div>

          <div class="chatbot__login-body">
              <p class="chatbot__login-hint">Isi data kamu untuk mulai chat dengan admin kami.</p>
              <form id="chatbot-login-form" class="chatbot__form" novalidate>
                  <div id="chatbot-login-error" class="chatbot__error" role="alert"></div>
                  <div class="chatbot__field">
                      <label for="guest-username">Username</label>
                      <input id="guest-username" type="text" required placeholder="Nama kamu" autocomplete="name" />
                  </div>
                  <div class="chatbot__field">
                      <label for="guest-email">Email</label>
                      <input id="guest-email" type="email" required placeholder="email@contoh.com" autocomplete="email" />
                  </div>
                  <div class="chatbot__field">
                      <label for="guest-phone">No. HP</label>
                      <input id="guest-phone" type="tel" required pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx" autocomplete="tel" />
                  </div>
                  <button type="submit" class="chatbot__btn">Mulai Chat</button>
              </form>
          </div>
      </div>

      <!-- CHAT VIEW -->
      <div class="chatbot__chat-interface" id="chatbot-interface" style="display: none;">
          <div class="chatbot__header">
              <div class="chatbot__avatar">
                  <img src="{{ asset('assets/logotab.png') }}" alt="Admin BAN PDM JATIM" />
                  <span class="chatbot__dot"></span>
              </div>
              <div class="chatbot__title">
                  <div class="chatbot__name">Admin BAN PDM JATIM</div>
                  <div class="chatbot__status">Online</div>
              </div>
              <button type="button" class="chatbot__action chatbot__logout" id="chatbot-logout">Logout</button>
              <button type="button" class="chatbot__action chatbot__close" aria-label="Tutup chat">
                  <i class="uil uil-multiply"></i>
              </button>
          </div>

          <ul class="chatbot__box">
              <li class="chatbot__chat incoming">
                  <img class="chatbot__msg-avatar" src="{{ asset('assets/logotab.png') }}" alt="" />
                  <div class="chatbot__bubble"><span id="welcome-msg">Halo! Ada yang bisa kami bantu?</span></div>
              </li>
          </ul>

          <div class="chatbot__inputbar">
              <textarea class="chatbot__textarea" placeholder="Tulis pesan..." rows="1" required></textarea>
              <button type="button" id="send-btn" class="chatbot__send" aria-label="Kirim pesan">
                  <i class="uil uil-message"></i>
              </button>
          </div>
      </div>
  </div>

  <!-- Chatbot Toggle Button -->
  <button class="chatbot__button" type="button" aria-label="Buka chat">
      <span class="chatbot__icon-open"><i class="uil uil-comment-dots"></i></span>
      <span class="chatbot__icon-close"><i class="uil uil-multiply"></i></span>
  </button>

  @include('frontend.partial.js')
  @vite(['resources/js/chat-frontend.js'])


</body>

</html>

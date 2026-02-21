<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" href="{{ asset('assets/cobaheader.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An impressive and flawless site template that includes various UI elements and countless features, attractive ready-made blocks and rich pages, basically everything you need to create a unique and professional website.">
    <meta name="keywords" content="Tailwind CSS, business, corporate, creative, gulp, marketing, minimal, modern, multipurpose, one page, responsive, saas, sass, seo, startup, html5 template, site template">
    <meta name="author" content="elemis">
    <title>BAN PDM JAWA TIMUR</title>
    @include('frontend.partial.style')
    <!-- Material Icons for Chatbot -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
  <!-- progress wrapper -->

  <!-- Progress Wrapper -->
  <div class="progress-wrap fixed w-[2.3rem] h-[2.3rem] cursor-pointer block shadow-[inset_0_0_0_0.1rem_rgba(128,130,134,0.25)] z-[1010] opacity-0 invisible translate-y-3 transition-all duration-[0.2s] ease-[linear,margin-right] delay-[0s] rounded-[100%] right-6 bottom-6 motion-reduce:transition-none after:absolute after:content-['\e951'] after:text-center after:leading-[2.3rem] after:text-[1.2rem] after:!text-[#605dba] after:h-[2.3rem] after:w-[2.3rem] after:cursor-pointer after:block after:z-[1] after:transition-all after:duration-[0.2s] after:ease-linear after:left-0 after:top-0 motion-reduce:after:transition-none after:font-Unicons">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
          <path class="fill-none stroke-[#605dba] stroke-[4] box-border transition-all duration-[0.2s] ease-linear motion-reduce:transition-none" d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
      </svg>
  </div>

  <!-- Chatbot Button -->
  <button class="chatbot__button">
      <span class="material-symbols-outlined">mode_comment</span>
      <span class="material-symbols-outlined">close</span>
  </button>

  <!-- Chatbot Container -->
  <div class="chatbot">
      <!-- Guest Login Form -->
      <div class="chatbot__login" id="chatbot-login">
          <div class="chatbot__header">
              <h3 class="chatbox__title">Chat Admin BAN PDM JATIM</h3>
          </div>
          <div class="chatbot__login-content">
{{--              <div class="chatbot__login-icon">--}}
{{--                  <span class="material-symbols-outlined">person</span>--}}
{{--              </div>--}}
{{--              <h4>Selamat Datang!</h4>--}}
{{--              <p>Silakan isi data Anda untuk memulai percakapan</p>--}}
              <form id="chatbot-login-form" class="chatbot__form">
                  <div class="chatbot__form-group">
                      <label for="guest-username">Username</label>
                      <input type="text" id="guest-username" placeholder="Masukkan username" required>
                  </div>
                  <div class="chatbot__form-group">
                      <label for="guest-email">Email</label>
                      <input type="email" id="guest-email" placeholder="email@contoh.com" required>
                  </div>
                  <div class="chatbot__form-group">
                      <label for="guest-phone">No. HP</label>
                      <input type="tel" id="guest-phone" placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}" required>
                  </div>
                  <button type="submit" class="chatbot__submit-btn">Mulai Chat</button>
              </form>
          </div>
      </div>

      <!-- Chat Interface -->
      <div class="chatbot__chat-interface" id="chatbot-interface" style="display: none;">
          <div class="chatbot__header">
              <h3 class="chatbox__title">Chatbot</h3>
              <div class="chatbot__header-actions">
                  <span class="chatbot__logout" id="chatbot-logout" title="Keluar">logout</span>
                  <span class="material-symbols-outlined chatbot__close">close</span>
              </div>
          </div>
          <ul class="chatbot__box">
              <li class="chatbot__chat incoming">
                  <span class="material-symbols-outlined">smart_toy</span>
                  <p id="welcome-msg">Hi there. How can I help you today?</p>
              </li>
          </ul>
          <div class="chatbot__input-box">
              <textarea class="chatbot__textarea" placeholder="Enter a message..." required></textarea>
              <span id="send-btn" class="material-symbols-outlined">send</span>
          </div>
      </div>
  </div>

  @include('frontend.partial.js')




</body>

</html>

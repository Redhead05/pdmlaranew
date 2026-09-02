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
    <!-- Material Icons for Chatbot -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ===== Chatbot Custom Styling (Frosted Glass Theme) ===== */
        .chatbot .chatbot__login,
        .chatbot .chatbot__chat-panel {
            font-family: 'Poppins', sans-serif;
        }

        .chatbot__glass-panel {
            /*background: rgba(15, 23, 42, 0.55);*/

            /*backdrop-filter: blur(12px);*/
            /*-webkit-backdrop-filter: blur(12px);*/
            /*border: 1px solid rgba(255, 255, 255, 0.15);*/
            /*border-radius: 20px;*/
            /*overflow: hidden;*/
            /*box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);*/
            /*transition: transform 0.3s ease, box-shadow 0.3s ease;*/

            /*background: rgba(15, 23, 42, 0.45);*/

            /* 2. Efek buram/blur pada elemen di belakangnya (Kunci utama Glassmorphism) */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px); /* Dukungan untuk Safari */

            /* 3. Border halus transparan untuk memberi efek kilauan kaca di tepian */
            border: 1px solid rgba(255, 255, 255, 0.1);

            /* 4. Lengkungan sudut dan bayangan lembut untuk efek kedalaman (pemanis) */
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .chatbot__glass-panel:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
        }

        .chatbot__msg-bubble {
            font-size: 12px;
            word-wrap: break-word;
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 999px;
        }

        .chatbot__msg-bubble.incoming {
            background: rgba(76, 76, 76, 0.85);
            color: #fff;
            border-bottom-left-radius: 6px;
        }

        .chatbot__msg-bubble.outgoing {
            background: linear-gradient(135deg, #22d3ee, #0ea5e9);
            color: #fff;
            border-bottom-right-radius: 6px;
        }

        .chatbot__notif-badge {
            background: #2796db;
            color: #fff;
            border-radius: 999px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        .chatbot__send-btn {
            background: linear-gradient(135deg, #22d3ee, #0ea5e9);
        }

        .chatbot__send-btn:hover {
            filter: brightness(1.1);
        }

        .chatbot__pfp {
            transition: transform 0.4s ease;
        }

        .chatbot__pfp:hover {
            transform: scale(1.15);
        }

        .chatbot__textarea::placeholder {
            color: #c6c6c6;
        }
        /* ===== Responsive Chatbot (Mobile) ===== */
        @media (max-width: 640px) {
            .chatbot__button {
                right: 16px !important;
                bottom: 16px !important;
                width: 52px;
                height: 52px;
            }

            .chatbot__login,
            .chatbot__chat-interface {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                max-height: 100vh !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }

            .chatbot__login .chatbot__glass-panel,
            .chatbot__chat-interface .chatbot__glass-panel {
                height: 100% !important;
                border-radius: 0 !important;
            }


    </style>
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

  <!-- Chatbot Container (must be BEFORE button for icon swap selector) -->
  <div class="chatbot" aria-live="polite">
      <!-- LOGIN VIEW -->
      <div class="chatbot__login" id="chatbot-login">
              <div class="chatbot__glass-panel">
                  <!-- Header -->
                  <div class="flex items-center justify-between px-4 py-3 text-white">
                      <div>
                          <div class="font-semibold text-xs leading-tight">Chat Admin</div>
                          <div class="text-[9px] text-white/70 leading-tight">Isi data untuk mulai chat</div>
                      </div>
                      <span class="material-symbols-outlined chatbot__close text-white/80 hover:text-white cursor-pointer" style="font-size:18px">close</span>
                  </div>

                  <div class="px-4 pb-4">
                      <form id="chatbot-login-form" class="chatbot__form space-y-2">
                          <div class="chatbot__form-group">
                              <label for="guest-username" class="text-white/80 text-[10px]">Username</label>
                              <input id="guest-username" type="text" required placeholder="Nama"
                                     class="w-full mt-1 rounded-full px-3 py-1.5 text-[11px] bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                          </div>
                          <div class="chatbot__form-group">
                              <label for="guest-email" class="text-white/80 text-[10px]">Email</label>
                              <input id="guest-email" type="email" required placeholder="email@contoh.com"
                                     class="w-full mt-1 rounded-full px-3 py-1.5 text-[11px] bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                          </div>
                          <div class="chatbot__form-group">
                              <label for="guest-phone" class="text-white/80 text-[10px]">No. HP</label>
                              <input id="guest-phone" type="tel" required pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx"
                                     class="w-full mt-1 rounded-full px-3 py-1.5 text-[11px] bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                          </div>

                          <button type="submit"
                                  class="chatbot__submit-btn chatbot__send-btn w-full px-3 py-2 rounded-full text-white active:scale-[0.98] transition text-[11px] font-semibold shadow-md">
                              Mulai Chat
                          </button>
                      </form>
                  </div>
              </div>
      </div>

      <!-- CHAT VIEW -->
      <div class="chatbot__chat-interface" id="chatbot-interface" style="display: none;">
          <div class="w-full h-full">
              <div class="chatbot__glass-panel flex flex-col h-full">

                  <!-- Header -->
                  <div class="flex items-center gap-2.5 px-4 py-3 text-white">
                      <div class="relative shrink-0">
                          <img src="{{ asset('assets/logotab.png') }}" alt="Admin"
                               class="chatbot__pfp w-9 h-9 rounded-full object-cover border-2 border-white/40 shadow-sm cursor-pointer" />
                          <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-slate-800"></span>
                      </div>

                      <div class="leading-tight flex-1 min-w-0">
                          <div class="font-semibold text-xs truncate">Admin BAN PDM JATIM</div>
                          <div class="text-[9px] text-white/70">Online</div>
                      </div>

{{--                      <div class="chatbot__notif-badge">3</div>--}}

                      <div class="flex items-center gap-3 shrink-0 ml-2">
                      <span class="chatbot__logout text-[10px] text-white/70 cursor-pointer hover:text-white hover:underline transition" id="chatbot-logout">
                          Logout
                      </span>
                          <span class="material-symbols-outlined chatbot__close text-white/70 hover:text-white cursor-pointer transition" style="font-size:18px">
                          close
                      </span>
                      </div>
                  </div>

                  <!-- Message list -->
                  <ul class="chatbot__box px-3 py-3 space-y-3 flex-1 overflow-y-auto">
                      <li class="chatbot__chat incoming flex items-end gap-2">
                          <img src="{{ asset('assets/logotab.png') }}" alt="" class="w-5 h-5 rounded-full object-cover shrink-0" />
                          <div class="chatbot__msg-bubble incoming">
                              <p id="welcome-msg">Halo! Ada yang bisa dibantu?</p>
                          </div>
                      </li>
                  </ul>

                  <!-- Input bar -->
                  <div class="px-3 py-3 border-t border-white/10">
                      <div class="flex items-center gap-2">
                          <div class="flex items-center gap-2 w-full rounded-full border border-white/20 bg-white/10 px-3 py-2 focus-within:ring-2 focus-within:ring-cyan-400 transition">
                          <textarea
                              class="chatbot__textarea w-full bg-transparent text-white placeholder-white/50 focus:outline-none text-xs leading-snug resize-none"
                              placeholder="Tulis pesan..."
                              rows="1"
                              required
                          ></textarea>
                          </div>

                          <button
                              type="button"
                              id="send-btn"
                              class="chatbot__send-btn shrink-0 w-9 h-9 rounded-full text-white flex items-center justify-center active:scale-95 transition shadow-sm"
                              aria-label="Send"
                          >
                              <span class="material-symbols-outlined" style="font-size:18px">send</span>
                          </button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Chatbot Toggle Button (ONLY ONE) -->
  <button class="chatbot__button relative z-[1020]" type="button" aria-label="Chat">
      <span class="material-symbols-outlined">mode_comment</span>
      <span class="material-symbols-outlined">close</span>
  </button>

  @include('frontend.partial.js')
  @vite(['resources/js/chat-frontend.js'])


</body>

</html>

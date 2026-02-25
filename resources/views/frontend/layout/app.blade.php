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
      <!-- LOGIN VIEW (guest fill username/email/phone) -->
      <div class="chatbot__login" id="chatbot-login">
          <div class="w-full h-full rounded-md">
               <div class="relative">
                   <div class="bg-slate-800 h-full relative overflow-hidden border border-slate-700 rounded-[15px]">
                        <!-- compact header -->
                       <div class="flex items-center justify-between px-3 py-1.5 bg-cyan-400 border-b border-cyan-500/40">
                           <div class="text-black">
                               <div class="font-semibold text-xs leading-tight">Chat Admin</div>
                               <div class="text-[9px] leading-tight">Isi data untuk mulai chat</div>
                           </div>
                           <button type="button" class="material-symbols-outlined chatbot__close text-black" style="font-size:18px; line-height:1">close</button>
                       </div>

                      <div class="px-3 py-2">
                            <form id="chatbot-login-form" class="chatbot__form space-y-1.5">
                                 <div class="chatbot__form-group">
                                   <label for="guest-username" class="text-black text-[10px]">Username</label>
                                  <input id="guest-username" type="text" required placeholder="Nama" class="w-full mt-1 rounded-md px-2 py-0.5 text-[10px] bg-slate-700 text-slate-100 border border-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                                 </div>
                                 <div class="chatbot__form-group">
                                   <label for="guest-email" class="text-black text-[10px]">Email</label>
                                  <input id="guest-email" type="email" required placeholder="email@contoh.com" class="w-full mt-1 rounded-md px-2 py-0.5 text-[10px] bg-slate-700 text-slate-100 border border-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                                 </div>
                                 <div class="chatbot__form-group">
                                   <label for="guest-phone" class="text-black text-[10px]">No. HP</label>
                                  <input id="guest-phone" type="tel" required pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx" class="w-full mt-1 rounded-md px-2 py-0.5 text-[10px] bg-slate-700 text-slate-100 border border-slate-600 focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400/60" />
                                 </div>

                              <button type="submit" class="chatbot__submit-btn w-full px-3 py-1.5 rounded-md bg-cyan-400 text-black hover:bg-cyan-500 active:scale-[0.99] transition text-[10px] font-semibold border border-cyan-300/30">
                                     Mulai Chat
                                 </button>
                             </form>
                         </div>
                      </div>
                 </div>
             </div>
         </div>

       <!-- CHAT VIEW -->
       <div class="chatbot__chat-interface" id="chatbot-interface" style="display: none;">
           <div class="w-full h-full rounded-md">
               <div class="bg-slate-800 h-full rounded-[15px] overflow-hidden border border-slate-700 flex flex-col">
                   <!-- Header -->
                   <div class="flex items-center gap-2 py-2 px-3 bg-cyan-400 border-b border-cyan-500/40">
                       <div class="flex items-center [&>*]:w-[1.4rem] [&>*]:h-[1.4rem] [&>*]:rounded-full [&>*]:bg-rose-400 [&>*]:p-0.5">
                           <div><img src="{{ asset('assets/logotab.png') }}" alt="" class="w-full h-full object-cover rounded-full"></div>
                       </div>

                       <div class="text-black leading-tight">
                           <div class="font-semibold text-xs">Admin BAN PDM JATIM</div>
                           <div class="text-[10px]">Reply in minutes</div>
                       </div>

                       <div class="ml-auto flex items-center gap-2">
                           <span class="chatbot__logout text-[10px] text-black/90 cursor-pointer hover:underline" id="chatbot-logout">logout</span>
                           <span class="material-symbols-outlined chatbot__close text-black cursor-pointer" style="font-size:18px">close</span>
                       </div>
                   </div>

                   <!-- Message list (scrollable) -->
                   <ul class="chatbot__box px-3 py-2 space-y-2 flex-1 overflow-y-auto">
                       <li class="chatbot__chat incoming flex gap-2">

                           <div class="text-[11px] p-2 w-[80%] bg-slate-600 text-slate-100 rounded-lg relative border border-white/5">
                               <p id="welcome-msg">Halo! Ada yang bisa dibantu?</p>
                           </div>
                       </li>
                   </ul>

                   <!-- Input bar (fixed at bottom) -->
                   <div class="px-2 py-2 bg-slate-900/80 backdrop-blur border-t border-slate-700">
                       <div class="flex items-end gap-2">
                           <div class="flex items-center gap-2 w-full rounded-xl border border-slate-600 bg-slate-800/60 px-2 py-1.5 focus-within:ring-2 focus-within:ring-cyan-400 focus-within:border-cyan-400">
                               <img src="{{ asset('assets/logotab.png') }}" alt="BAN-PDM" class="w-5 h-5 rounded-full object-cover border border-white/10" />
                               <textarea class="chatbot__textarea w-full bg-transparent text-slate-100 placeholder-slate-400 focus:outline-none text-xs leading-snug" placeholder="Tulis pesan..." rows="1" required></textarea>
                           </div>

                           <button type="button" id="send-btn" class="shrink-0 w-9 h-9 rounded-full bg-cyan-400 text-white flex items-center justify-center hover:bg-cyan-500 transition border border-cyan-300/30 shadow-sm" aria-label="Send">
                               <span class="material-symbols-outlined" style="font-size:18px">send</span>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       </div>

  </div>

  <!-- Chatbot Toggle Button (ONLY ONE) -->
  <button class="chatbot__button" type="button" aria-label="Chat">
      <span class="material-symbols-outlined">mode_comment</span>
      <span class="material-symbols-outlined">close</span>
  </button>

  @vite(['resources/js/chat-frontend.js'])
  @include('frontend.partial.js')


</body>

</html>

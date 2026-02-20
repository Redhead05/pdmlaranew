<header class="relative wrapper">
    <nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-dark">
          <div class="container xl:!flex-row lg:!flex-row !flex-nowrap items-center">
            <div class="navbar-brand w-full">
              <a href="{{ route('frontend.pages.home') }}">
                <img class="logo-dark" src="{{ asset ('assets/cobaheader.png')}}" alt="Logo BANPDM Jatim" style="max-height:90px;width:auto;object-fit:contain;">
                <img class="logo-light" src="{{ asset ('assets/cobaheader.png')}}" alt="Logo BANPDM Jatim" style="max-height:90px;width:auto;object-fit:contain;">
              </a>
            </div>
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            <div class="offcanvas-header xl:!hidden lg:!hidden flex items-center justify-between flex-row p-6">
              <h3 class="!text-white xl:!text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !mb-0">BAN PDM JAWA TIMUR</h3>
              <button type="button" class="btn-close btn-close-white !mr-[-0.75rem] m-0 p-0 leading-none !text-[#343f52] transition-all duration-[0.2s] ease-in-out border-0 motion-reduce:transition-none before:text-[1.05rem] before:text-white before:content-['\ed3b'] before:w-[1.8rem] before:h-[1.8rem] before:leading-[1.8rem] before:shadow-none before:transition-[background] before:duration-[0.2s] before:ease-in-out before:!flex before:justify-center before:items-center before:m-0 before:p-0 before:rounded-[100%] hover:no-underline bg-inherit before:bg-[rgba(255,255,255,.08)] before:font-Unicons hover:before:bg-[rgba(0,0,0,.11)]  " data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
              <div class="offcanvas-body xl:!ml-auto lg:!ml-auto flex  flex-col !h-full">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link !text-[.85rem] !tracking-[normal]" href="{{ route('frontend.pages.home') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown dropdown-mega">
                         <a class="nav-link !text-[.85rem] !tracking-[normal]" href="{{ route('frontend.pages.gallery') }}">Galeri</a>
                    </li>
                    <li class="nav-item dropdown dropdown-mega">
                         <a class="nav-link !text-[.85rem] !tracking-[normal]" href="{{ route('frontend.pages.news') }}">Berita</a>
                    </li>
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link !text-[.85rem] !tracking-[normal]" href="{{route('frontend.pages.employes')}}">Struktur Organisasi</a>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
                <div class="offcanvas-footer xl:!hidden lg:!hidden">
                  <div>
                    <a href="mailto:first.last@email.com" class="link-inverse">info@email.com</a>
                    <br> 00 (123) 456 78 90 <br>
                    <nav class="nav social social-white !mt-4">
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-twitter before:content-['\ed59'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-facebook-f before:content-['\eae2'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-dribbble before:content-['\eaa2'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-instagram before:content-['\eb9c'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-youtube before:content-['\edb5'] !text-white text-[1rem]"></i></a>
                    </nav>
                    <!-- /.social -->
                  </div>
                </div>
                <!-- /.offcanvas-footer -->
              </div>
              <!-- /.offcanvas-body -->
            </div>
            <!-- /.navbar-collapse -->
              <div class="navbar-other w-full !flex !ml-auto">
                  <ul class="navbar-nav !flex-row !items-center !ml-auto">
                      <li class="nav-item xl:!hidden lg:!hidden">
                          <button class="hamburger offcanvas-nav-btn"><span></span></button>
                      </li>
                  </ul>
                  <!-- /.navbar-nav -->
              </div>
            <!-- /.navbar-other -->
          </div>
          <!-- /.container -->
        </nav>
        <!-- /.navbar -->
      <div class="offcanvas offcanvas-end text-inverse !text-[#cacaca] opacity-100" id="offcanvas-info" data-bs-scroll="true">
        <div class="offcanvas-header flex flex-row items-center justify-between p-[1.5rem]">
          <h3 class="!text-white xl:!text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !leading-[1.4] !mb-0">BAN PDM JAWA TIMUR</h3>
          <button type="button" class="btn-close btn-close-white !mr-[-0.5rem] m-0 p-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- /.offcanvas-body -->
      </div>
        <!-- /.offcanvas -->
      </header>
    <section class="video-wrapper relative overflow-hidden bg-overlay bg-overlay-gradient !px-0 !mt-0 min-h-[80vh] xl:rounded-[1rem] lg:rounded-[1rem] md:rounded-[1rem]">
            <video poster="{{ asset ('assets/fe/assets/img/photos/timeline1.png')}}" src="{{ asset ('assets/fe/assets/media/movie4.mp4')}}" autoplay loop playsinline muted></video>
            <div class="video-content absolute z-[2] w-full h-full flex items-center justify-center text-center flex-col left-0 top-0">
              <div class="container !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                  <div class="lg:w-8/12 xl:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !text-center !text-white !mx-auto">
                    <h1 class="xl:!text-[2.7rem] !text-[calc(1.395rem_+_1.74vw)] font-semibold !leading-[1.15] !text-white !mb-5">
                        <span class="rotator-zoom">Menyenangkan,Mudah,Gratis</span></h1>
                    <p class="lead !text-[1.2rem] !leading-[1.6] font-medium !mb-0 xxl:!mx-8">Akreditasi Gratis Untuk Semua Lembaga di Indonesia</p>
                  </div>
                  <!-- /column -->
                </div>
              </div>
              <!-- /.video-content -->
            </div>
            <!-- /.content-overlay -->
    </section>

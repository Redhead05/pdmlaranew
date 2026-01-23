<header class="relative wrapper">
        <nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-dark">
          <div class="container xl:!flex-row lg:!flex-row !flex-nowrap items-center">
            <div class="navbar-brand w-full">
              <a href="{{ route('frontend.pages.home') }}">
                <img class="logo-dark" src="{{ asset ('assets/logo_BANPDMJATIM.png')}}" alt="Logo BANPDM Jatim" style="max-height:90px;width:auto;object-fit:contain;">
                <img class="logo-light" src="{{ asset ('assets/logo_BANPDMJATIM.png')}}" alt="Logo BANPDM Jatim" style="max-height:90px;width:auto;object-fit:contain;">
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
                        <a class="nav-link !text-[.85rem] !tracking-[normal]" href="#">Akreditasi</a>
                    </li>
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link !text-[.85rem] !tracking-[normal]" href="{{route('frontend.pages.employes')}}">Struktur Organisasi</a>
                    </li>

{{--                  <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle !text-[.85rem] !tracking-[normal]" href="#" data-bs-toggle="dropdown">Projects</a>--}}
{{--                    <div class="dropdown-menu dropdown-lg">--}}
{{--                      <div class="dropdown-lg-content">--}}
{{--                        <div>--}}
{{--                          <h6 class="dropdown-header !text-[#747ed1]">Project Pages</h6>--}}
{{--                          <ul class="pl-0 list-none">--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./projects.html">Projects I</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./projects2.html">Projects II</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./projects3.html">Projects III</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./projects4.html">Projects IV</a></li>--}}
{{--                          </ul>--}}
{{--                        </div>--}}
{{--                        <!-- /.column -->--}}
{{--                        <div>--}}
{{--                          <h6 class="dropdown-header !text-[#747ed1]">Single Projects</h6>--}}
{{--                          <ul class="pl-0 list-none">--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./single-project.html">Single Project I</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./single-project2.html">Single Project II</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./single-project3.html">Single Project III</a></li>--}}
{{--                            <li><a class="dropdown-item hover:!text-[#747ed1]" href="./single-project4.html">Single Project IV</a></li>--}}
{{--                          </ul>--}}
{{--                        </div>--}}
{{--                        <!-- /.column -->--}}
{{--                      </div>--}}
{{--                      <!-- /auto-column -->--}}
{{--                    </div>--}}
{{--                  </li>--}}
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
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-info" aria-label="Info" title="Info">
                    <i class="uil uil-info-circle before:content-['\eb99'] !text-[1.1rem]"></i>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-info" aria-label="Help" title="Help">
                    <i class="uil uil-question-circle before:content-['\eb9a'] !text-[1.1rem]"></i>
                  </a>
                </li>
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
        <div class="offcanvas-body !pb-[1.5rem]">
          <div class="widget !mb-8">
            <p>BAN PDM JAWA TIMUR is a multipurpose HTML5 template with various layouts which will be a great solution for your business.</p>
          </div>
          <!-- /.widget -->
          <div class="widget !mb-8">
            <h4 class="widget-title !text-white !mb-[0.75rem] !text-[1rem] !leading-[1.45]">Contact Info</h4>
            <address class=" not-italic !leading-[inherit] !mb-[1rem]"> Moonshine St. 14/05 <br> Light City, London </address>
            <a class="!text-[#cacaca] hover:!text-[#747ed1]" href="mailto:first.last@email.com">info@email.com</a><br> 00 (123) 456 78 90
          </div>
          <!-- /.widget -->
          <div class="widget !mb-8">
            <h4 class="widget-title !text-white !mb-[0.75rem] !text-[1rem] !leading-[1.45]">Learn More</h4>
            <ul class="list-unstyled !pl-0">
              <li><a class="!text-[#cacaca] hover:!text-[#747ed1]" href="#">Our Story</a></li>
              <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#747ed1]" href="#">Terms of Use</a></li>
              <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#747ed1]" href="#">Privacy Policy</a></li>
              <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#747ed1]" href="#">Contact Us</a></li>
            </ul>
          </div>
          <!-- /.widget -->
          <div class="widget">
            <h4 class="widget-title !text-white !mb-[0.75rem] !text-[1rem] !leading-[1.45]">Follow Us</h4>
            <nav class="nav social social-white">
              <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-twitter before:content-['\ed59'] !text-white text-[1rem]"></i></a>
              <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-facebook-f before:content-['\eae2'] !text-white text-[1rem]"></i></a>
              <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-dribbble before:content-['\eaa2'] !text-white text-[1rem]"></i></a>
              <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-instagram before:content-['\eb9c'] !text-white text-[1rem]"></i></a>
              <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class="uil uil-youtube before:content-['\edb5'] !text-white text-[1rem]"></i></a>
              </nav>
            <!-- /.social -->
          </div>
          <!-- /.widget -->
        </div>
        <!-- /.offcanvas-body -->
      </div>
        <!-- /.offcanvas -->
      </header>
    <section class="video-wrapper relative overflow-hidden bg-overlay bg-overlay-gradient !px-0 !mt-0 min-h-[80vh] xl:rounded-[1rem] lg:rounded-[1rem] md:rounded-[1rem]">
            <video poster="{{ asset ('assets/fe/assets/img/photos/movie2.jpg')}}" src="{{ asset ('assets/fe/assets/media/movie2.mp4')}}" autoplay loop playsinline muted></video>
            <div class="video-content absolute z-[2] w-full h-full flex items-center justify-center text-center flex-col left-0 top-0">
              <div class="container !text-center">
                <div class="flex flex-wrap mx-[-15px]">
                  <div class="lg:w-8/12 xl:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !text-center !text-white !mx-auto">
                    <h1 class="xl:!text-[2.7rem] !text-[calc(1.395rem_+_1.74vw)] font-semibold !leading-[1.15] !text-white !mb-5">
                        <span class="rotator-zoom">Menyenangkan,Mudah,Gratis</span></h1>
                    <p class="lead !text-[1.2rem] !leading-[1.6] font-medium !mb-0 xxl:!mx-8">Proses Akreditasi 0 Rupiah</p>
                  </div>
                  <!-- /column -->
                </div>
              </div>
              <!-- /.video-content -->
            </div>
            <!-- /.content-overlay -->
    </section>

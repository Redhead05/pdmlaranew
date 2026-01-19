@extends('frontend.layout.app')
@section('content')

        <section class="wrapper !bg-[#ffffff] angled upper-end lower-end">
          <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] md:mx-[-20px] lg:mx-[-20px] xl:mx-[-35px] !mt-[-30px] items-center">
              <div class="md:w-8/12 lg:w-6/12 xl:w-6/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full xl:!order-2 lg:!order-2 !mx-auto !mt-[30px]">
                <div class="img-mask mask-2"><img src="{{ asset ('assets/images/human.jpeg') }}" alt="image"></div>
              </div>
              <!--/column -->
              <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full !mt-[30px]">
                <h2 class="!text-[calc(1.285rem_+_0.42vw)] font-bold xl:!text-[1.6rem] !leading-[1.3] !mb-3">About Me</h2>
                <p class="lead !text-[1.05rem] !leading-[1.6] font-medium">I'm Caitlyn Sandbox, a photographer specializing in food, drink and product photography.</p>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Vestibulum id ligula.</p>
                <p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Lorem ipsum dolor sit amet, consectetur.</p>
                <a href="#" class="btn btn-primary !text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:!border-[#3f78e0]   active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] !mt-2 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]">Learn More</a>
              </div>
              <!--/column -->
            </div>

          </div>
        </section>
              <!-- /section -->
               <section class="wrapper !bg-[#ffffff]">
                <div class="container py-[5rem] xl:!py-[7rem] lg:!py-[7rem] md:!py-[7rem]">
                    <div class="flex flex-wrap mx-[-15px] !text-center">
                        <div class="md:w-10/12 lg:w-7/12 xl:w-7/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !relative">
                            <img src="{{ asset ('assets/fe/assets/img/svg/doodle5.svg') }}" class="!w-[5rem] absolute hidden xl:block lg:block" data-delay="1800" style="bottom: -60%; right: 10%" alt="image">
                            <img src="{{ asset ('assets/fe/assets/img/svg/doodle6.svg') }}" class="!h-[5rem] !absolute hidden xl:block lg:block" data-delay="1800" style="top: -40%; left: -5%" alt="image">
                            <h2 class="!text-[0.8rem] !tracking-[0.02rem] uppercase !text-[#aab0bc] !mb-3 !leading-[1.35]">How It Works</h2>
                            <h3 class="!text-[calc(1.325rem_+_0.9vw)] font-bold !leading-[1.2] xl:!text-[2rem] !mb-8 xl:!px-6">Download the app, create your profile and <span class="text-gradient gradient-7">voilà</span>, you're all set!</h3>
                        </div>
                        <!-- /column -->
                    </div>
                    <!-- /.row -->
                    <div class="flex flex-wrap mx-[-15px] lg:!mb-40 xl:!mb-[17.5rem]">
                        <div class="xxl:w-11/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
                            <div class="flex flex-wrap mx-[-15px] !mt-[-50px] xl:!mt-0 lg:!mt-0 !text-center items-center">
                                <div class="md:w-6/12 lg:w-4/12 xl:w-4/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !mb-[-2.5rem] lg:!mb-0 xl:!mb-0 !mt-[50px] xl:!mt-0 lg:!mt-0">
                                    <figure class="mx-auto"><img src="{{ asset ('assets/fe/assets/img/photos/devices3.png') }}" srcset="{{ asset ('assets/fe/assets/img/photos/devices4@2x.png') }} 2x" alt="image"></figure>
                                </div>
                                <!-- /column -->
                                <div class="md:w-6/12 lg:w-4/12 xl:w-4/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !mb-[-2.5rem] lg:!mb-0 xl:!mb-0 !mt-[50px] xl:!mt-0 lg:!mt-0">
                                    <figure class="mx-auto"><img src="{{ asset ('assets/fe/assets/img/photos/devices4.png') }}" srcset="{{ asset ('assets/fe/assets/img/photos/devices4@2x.png') }} 2x" alt="image"></figure>
                                </div>
                                <!-- /column -->
                                <div class="md:w-6/12 lg:w-4/12 xl:w-4/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !mb-[-2.5rem] lg:!mb-0 xl:!mb-0 !mt-[50px] xl:!mt-0 lg:!mt-0">
                                    <figure class="mx-auto"><img src="{{ asset ('assets/fe/assets/img/photos/devices4.png') }}" srcset="{{ asset ('assets/fe/assets/img/photos/devices4@2x.png') }} 2x" alt="image"></figure>
                                </div>
                                <!-- /column -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /column -->
                    </div>
                <!-- /.container -->
              </section>
              <!-- /section -->
              <section class="wrapper !bg-[#ffffff]">
                <div class="container py-[5rem] xl:!py-[7rem] lg:!py-[7rem] md:!py-[7rem]">
                  <div class="flex flex-wrap mx-[-15px] !text-center">
                    <div class="lg:w-10/12 xl:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !relative">
                      <div class="!relative">
                        <div class="shape pale-pink rellax !w-[8rem] !h-[8rem] !absolute z-[1]" data-rellax-speed="1" style="top: 1rem; left: -4.2rem;">
                          <img src="{{ asset ('assets/fe/assets/img/svg/hex.svg')}}" class="svg-inject icon-svg !w-full !h-full" alt="image">
                        </div>
                        <div class="shape pale-purple rellax !w-[8rem] !h-[8rem] !absolute z-[1]" data-rellax-speed="1" style="bottom: 2rem; right: -3.5rem;">
                          <img src="{{ asset ('assets/fe/assets/img/svg/circle.svg')}}" class="svg-inject icon-svg !w-full !h-full" alt="image">
                        </div>
                        <video poster="{{ asset ('assets/fe/assets/img/photos/movie.jp')}}g" class="player relative z-[2] rounded-[0.4rem]" playsinline controls preload="none">
                          <source src="{{ asset ('assets/fe/assets/media/movie.mp4')}}" type="video/mp4">
                          <source src="{{ asset ('assets/fe/assets/media/movie.webm')}}" type="video/webm">
                        </video>
                      </div>
                    </div>
                    <!--/column -->
                  </div>
                  <!--/.row -->
                  <div class="flex flex-wrap mx-[-15px] !text-center !mt-[3.5rem]">
                    <div class="xl:w-10/12 lg:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
        {{--              <h2 class="!text-[0.8rem] !tracking-[0.02rem] uppercase !text-[#aab0bc] !mb-3 !leading-[1.35]">Liputan Proses Bisnis BAN PDM JAWA TIMUR</h2>--}}
                      <h3 class="xl:!text-[2.1rem] !text-[calc(1.335rem_+_1.02vw)] !leading-[1.2] font-semibold xl:!px-10 xxl:!px-20 !mb-10">Liputan Proses Bisnis BAN PDM JAWA TIMUR</h3>
                      <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] process-wrapper arrow !text-center">
                        <div class="xl:w-4/12 lg:w-4/12 md:w-4/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 255.98" data-inject-url="https://BAN PDM JAWA TIMUR-tailwind-template.netlify.app/assets/img/icons/solid/bulb.svg" class="svg-inject icon-svg !w-[2.2rem] !h-[2.2rem]  solid-duo text-purple-pink !mb-4 m-[0_auto]"><circle class="fill-primary" cx="58.67" cy="149.31" r="32"></circle><path class="fill-primary" d="M88 202.65H29.33A29.36 29.36 0 000 232v16a8 8 0 008 8h101.33a8 8 0 008-8v-16A29.36 29.36 0 0088 202.65z"></path><circle class="fill-primary" cx="197.33" cy="149.31" r="32"></circle><path class="fill-primary" d="M226.67 202.65H168A29.36 29.36 0 00138.67 232v16a8 8 0 008 8H248a8 8 0 008-8v-16a29.36 29.36 0 00-29.33-29.35z"></path><path class="fill-secondary" d="M149.76 108.48v7.68A11.9 11.9 0 01137.81 128h-19.63c-5.76 0-12-4.27-12-13.76v-5.76zM176 47.68a47.26 47.26 0 01-17.6 36.91 22.89 22.89 0 00-8.32 13.23H106a20 20 0 00-7.79-12.69A47.13 47.13 0 0180 46.73C80.53 21.34 101.76.33 127.25 0a47.34 47.34 0 0134.56 13.88A46.82 46.82 0 01176 47.68z"></path></svg>
                          <h3 class="!text-[1.1rem]">1. Collect Ideas</h3>
                          <p>Etiam porta malesuada magna mollis euismod consectetur leo elit.</p>
                        </div>
                        <!--/column -->
                        <div class="xl:w-4/12 lg:w-4/12 md:w-4/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" data-inject-url="https://BAN PDM JAWA TIMUR-tailwind-template.netlify.app/assets/img/icons/solid/compare.svg" class="svg-inject icon-svg !w-[2.2rem] !h-[2.2rem]  solid-duo text-purple-pink !mb-4 m-[0_auto]"><path class="fill-primary" d="M123.22 148.5A8 8 0 00116 144H19.36a8 8 0 00-8 8c0 57.34 46.94 104 104.66 104a104.67 104.67 0 0045.45-10.29 8 8 0 003.76-10.71z"></path><path class="fill-secondary" d="M152 19a8 8 0 00-8 8v95.39a7.93 7.93 0 00.8 3.49l41.73 85.95a8 8 0 0010.65 3.73c36.29-17.48 58.82-53.19 58.82-93.19C256 65.36 209.34 19 152 19z"></path><path class="fill-primary" d="M112 104V8a8 8 0 00-8-8A104.13 104.13 0 000 104a8 8 0 008 8h96a8 8 0 008-8z"></path></svg>
                          <h3 class="!text-[1.1rem]">2. Data Analysis</h3>
                          <p>Etiam porta malesuada magna mollis euismod consectetur leo elit.</p>
                        </div>
                        <!--/column -->
                        <div class="xl:w-4/12 lg:w-4/12 md:w-4/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" data-inject-url="https://BAN PDM JAWA TIMUR-tailwind-template.netlify.app/assets/img/icons/solid/delivery-box.svg" class="svg-inject icon-svg !w-[2.2rem] !h-[2.2rem]  solid-duo text-purple-pink !mb-4 m-[0_auto]"><path class="fill-secondary" d="M172 20.57L55.79 80 9.6 57.32 123.71 1A9.43 9.43 0 01132 1zm74.27 36.75l-118.21 58.56-44.27-21.8-6.38-3.25L193.7 31.36l6.4 3.24z"></path><path class="fill-primary" d="M118.59 132.76L118.46 256 5.23 196.91A9.89 9.89 0 010 188.22V74.2l48 23.64v41.55a9.6 9.6 0 1019.2 0v-31.94l6.4 3.12zm137.28-58.43l-118.08 58.31-.13 123.23L256 194.08z"></path></svg>
                          <h3 class="!text-[1.1rem]">3. Finalize Product</h3>
                          <p>Etiam porta malesuada magna mollis euismod consectetur leo elit.</p>
                        </div>
                        <!--/column -->
                      </div>
                      <!--/.row -->
                    </div>
                    <!-- /column -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.container -->
              </section>

              <section class="wrapper !bg-[#ffffff]">
                <div class="container py-[5rem] xl:!py-[7rem] lg:!py-[7rem] md:!py-[7rem]">
          <!-- Centered headings -->
                  <div class="mx-auto max-w-5xl text-center">
                    <h2 class="!text-[0.8rem] uppercase !text-[#aab0bc] !mb-3 !leading-[1.35]">Our Client</h2>
                    <h3 class="xl:!text-[2.1rem] !text-[calc(1.335rem_+_1.02vw)] !leading-[1.2] font-semibold !mb-3 xxl:!pr-5">Bekerjasama dengan Dinas Pendidikan dan Kementrian Agama Se-Jawa Timur</h3>
                    <p class="lead !text-[1.1rem] !leading-[1.55] font-medium !mb-8 xxl:!pr-5">Serta Organisasi Mitra</p>
                  </div>

                  <!-- Logos grid below headings -->
                  <div class="mt-6 flex flex-wrap -mx-3 justify-center items-center">
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z1.png') }}" alt="brand 1" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z2.png') }}" alt="brand 2" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z3.png') }}" alt="brand 3" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z4.png') }}" alt="brand 4" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z5.png') }}" alt="brand 5" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z6.png') }}" alt="brand 6" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z7.png') }}" alt="brand 7" class="w-full h-auto object-contain"></figure>
                    </div>
                    <div class="w-6/12 md:w-3/12 px-3 mb-6 flex justify-center">
                      <figure class="w-full max-w-[160px]"><img src="{{ asset ('assets/fe/assets/img/brands/z8.png') }}" alt="brand 8" class="w-full h-auto object-contain"></figure>
                    </div>
                  </div>
                </div>

                <!-- /.container -->
              </section>

@endsection

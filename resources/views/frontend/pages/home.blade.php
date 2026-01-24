@extends('frontend.layout.app')
@section('content')

    <section class="wrapper !bg-[#ffffff] ">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] items-center">
                <div class="md:w-8/12 lg:w-6/12 xl:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px] xl:!order-2 lg:!order-2 !relative">
                    <div class="shape !bg-[#edf2fc] !rounded-[50%] rellax !w-[10rem] !h-[10rem] absolute z-[1]" data-rellax-speed="1" style="top: -2rem; right: -1.9rem;"></div>
{{--                    <figure class="!rounded-[.4rem] z-[2] relative"><img class="!rounded-[.4rem] " src="{{ asset ('assets/fe/assets/img/photos/about7.jpg') }}" srcset="{{ asset ('assets/fe/assets/img/photos/about7@2x.jpg') }} 2x" alt="image"></figure>--}}
                    <figure class="!rounded-[.4rem] z-[2] relative">
                        <img class="!rounded-[.4rem] " src="{{ asset ('assets/blankphotopdm/ExamEmployee.png') }}"
                             srcset="{{ asset ('assets/fe/assets/img/photos/about7@2x.jpg') }} 2x" alt="image">
                    </figure>
                </div>
                <!--/column -->
                <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <h2 class="text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-3">Who Are We?</h2>
                    <p class="lead !text-[1.05rem] !leading-[1.6] font-medium">We are a digital and branding company that believes in the power of creative strategy and along with great design.</p>
                    <p class="!mb-6">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p>
                    <div class="flex flex-wrap mx-[-15px] xl:mx-[-25px] !mt-[-30px]">
                        <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:!px-[25px] !px-[15px] max-w-full !mt-[30px]">
                            <div class="flex flex-row">
                                <div>
                                    <img src="{{ asset ('assets/fe/assets/img/icons/lineal/target.svg') }}" class="svg-inject icon-svg !w-[2.2rem] !h-[2.2rem]  !mr-4" alt="image">
                                </div>
                                <div>
                                    <h4 class="!mb-1">Our Mission</h4>
                                    <p class="!mb-0">Dapibus eu leo quam ornare curabitur blandit tempus.</p>
                                </div>
                            </div>
                        </div>
                        <!--/column -->
                        <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:!px-[25px] !px-[15px] max-w-full !mt-[30px]">
                            <div class="flex flex-row">
                                <div>
                                    <img src="{{ asset ('assets/fe/assets/img/icons/lineal/award-2.svg') }}" class="svg-inject icon-svg !w-[2.2rem] !h-[2.2rem]  !mr-4" alt="image">
                                </div>
                                <div>
                                    <h4 class="!mb-1">Our Values</h4>
                                    <p class="!mb-0">Aenean lacinia bibendum nulla sed consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <!--/column -->
                    </div>
                    <!--/.row -->
                </div>
                <!--/column -->
            </div>
            <!--/.row -->
        </div>
        <!-- /.container -->
    </section>

    <!-- /section -->
            <section class="wrapper !bg-[#ffffff]">
                    <div class="container max-w-5xl mx-auto px-4 py-12 xl:py-16">
                        <div class="flex flex-wrap mx-[-15px] !text-center">
                            <div class="md:w-10/12 lg:w-7/12 xl:w-7/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !relative">
                                <img src="{{ asset ('assets/fe/assets/img/svg/doodle5.svg') }}" class="!w-[5rem] absolute hidden xl:block lg:block" data-delay="1800" style="bottom: -60%; right: 10%" alt="image">
                                <img src="{{ asset ('assets/fe/assets/img/svg/doodle6.svg') }}" class="!h-[5rem] !absolute hidden xl:block lg:block" data-delay="1800" style="top: -40%; left: -5%" alt="image">
    {{--                            <h2 class="!text-[0.8rem] !tracking-[0.02rem] uppercase !text-[#aab0bc] !mb-3 !leading-[1.35]">How It Works</h2>--}}
                                <h3 class="!text-[calc(1.325rem_+_0.9vw)] font-bold !leading-[1.2] xl:!text-[2rem] !mb-8 xl:!px-6">Masuk dan Login Ke Sispena, Untuk melanjutkan proses <span class="text-gradient gradient-7">akreditasi</span>, Demi Anak Bangsa!</h3>
                            </div>
                            <!-- /column -->
                        </div>
                        <!-- /.row -->
                        <div class="mt-6 container max-w-5xl mx-auto px-4 py-12 xl:py-16">
                            <div class="flex flex-wrap justify-center items-center gap-6 px-4">
                                <div class="w-full xxl:w-11/12">
                                    <div class="flex flex-wrap items-center justify-center gap-6 mt-6">
                                        <div class="w-full md:w-4/12 lg:w-3/12 xl:w-3/12 px-4">
                                            <figure class="mx-auto w-full max-w-[160px]">
                                                <img src="{{ asset('assets/fe/assets/img/photos/SispenaPaud.png') }}" srcset="{{ asset('assets/fe/assets/img/photos/SispenaPaud@2x.png') }} 2x" alt="SispenaPaud" class="w-full h-auto object-contain block">
                                            </figure>
                                        </div>
                                        <div class="w-full md:w-6/12 lg:w-4/12 xl:w-4/12 px-4">
                                            <figure class="mx-auto">
                                                <img src="{{ asset('assets/fe/assets/img/photos/devices4.png') }}" srcset="{{ asset('assets/fe/assets/img/photos/devices4@2x.png') }} 2x" alt="devices" class="w-full h-auto object-contain">
                                            </figure>
                                        </div>
                                        <div class="w-full md:w-4/12 lg:w-3/12 xl:w-3/12 px-4">
                                            <figure class="mx-auto w-full max-w-[160px]">
                                                <img src="{{ asset('assets/fe/assets/img/photos/sispenasm.png') }}" srcset="{{ asset('assets/fe/assets/img/photos/devices4@2x.png') }} 2x" alt="sispenasm" class="w-full h-auto object-contain block">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
{{--                    <h2 class="!text-[0.8rem] uppercase !text-[#aab0bc] !mb-3 !leading-[1.35]">Our Client</h2>--}}
                    <h3 class="xl:!text-[2.1rem] !text-[calc(1.335rem_+_1.02vw)] !leading-[1.2] font-semibold !mb-3 xxl:!pr-5">Mitra Kami </h3>
{{--                    <p class="lead !text-[1.1rem] !leading-[1.55] font-medium !mb-8 xxl:!pr-5">Serta Organisasi Mitra</p>--}}
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
    <section class="wrapper !bg-[#ffffff]">
        <div class="container py-[5rem] xl:!py-[7rem] lg:!py-[7rem] md:!py-[7rem]">
            <div class="md:w-10/12 lg:w-7/12 xl:w-7/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !relative">
                <img src="{{ asset ('assets/fe/assets/img/svg/doodle5.svg') }}" class="!w-[5rem] absolute hidden xl:block lg:block" data-delay="1800" style="bottom: -60%; right: 10%" alt="image">
                <img src="{{ asset ('assets/fe/assets/img/svg/doodle6.svg') }}" class="!h-[5rem] !absolute hidden xl:block lg:block" data-delay="1800" style="top: -40%; left: -5%" alt="image">
                <h3 class="!text-[calc(1.325rem_+_0.9vw)] font-bold !leading-[1.2] xl:!text-[2rem] !mb-8 xl:!px-6">Pertanyaan umum <span class="text-gradient gradient-7">FAQ</span></h3>
            </div>
            <div class="accordion accordion-wrapper" id="accordionExample">
                <div class="card accordion-item !mb-5">
                    <div class="card-header !mb-0 !p-[.9rem_1.3rem_.85rem] !border-0 !bg-inherit" id="headingOne">
                        <button class="accordion-button !text-[.85rem] before:!text-[#3f78e0] hover:!text-[#3f78e0]" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Professional Design </button>
                    </div>
                    <!--/.card-header -->
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="card-body flex-[1_1_auto] p-[0_1.25rem_.25rem_2.35rem]">
                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.accordion-collapse -->
                </div>
                <!--/.accordion-item -->
                <div class="card accordion-item !mb-5">
                    <div class="card-header !mb-0 !p-[.9rem_1.3rem_.85rem] !border-0 !bg-inherit text-center" id="headingTwo">
                        <button class="collapsed !text-[.85rem] before:!text-[#3f78e0] hover:!text-[#3f78e0]" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Top-Notch Support
                        </button>
                    </div>
                    <!--/.card-header -->
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="card-body flex-[1_1_auto] p-[0_1.25rem_.25rem_2.35rem]">
                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.accordion-collapse -->
                </div>
                <!--/.accordion-item -->
                <div class="card accordion-item !mb-5">
                    <div class="card-header !mb-0 !p-[.9rem_1.3rem_.85rem] !border-0 !bg-inherit" id="headingThree">
                        <button class="collapsed !text-[.85rem] before:!text-[#3f78e0] hover:!text-[#3f78e0]" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Header and Slider Options </button>
                    </div>
                    <!--/.card-header -->
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="card-body flex-[1_1_auto] p-[0_1.25rem_.25rem_2.35rem]">
                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.accordion-collapse -->
                </div>
                <!--/.accordion-item -->
            </div>
        </div>
    </section>

@endsection

@extends('frontend.layout.app')
@section('content')
    <section class="wrapper !bg-[#edf2fc]">
        <div class="container pt-10 pb-36 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] xl:pb-40 lg:pb-40 md:pb-40 !text-center">
            <div class="flex flex-wrap mx-[-15px]">
                <div class="md:w-10/12 lg:w-10/12 xl:w-8/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
                    <div class="post-header !mb-[.9rem]">
                        <div class="inline-flex uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] !mb-[0.4rem]  text-line relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                            <a href="#" class="hover" rel="category">Teamwork</a>
                        </div>
                        <!-- /.post-category -->
                        <h1 class="!text-[calc(1.365rem_+_1.38vw)] font-bold !leading-[1.2] xl:!text-[2.4rem] !mb-4">Commodo Dolor Bibendum Parturient Cursus Mollis</h1>
                        <ul class="!text-[0.8rem] !text-[#aab0bc] m-0 p-0 list-none !mb-5">
                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>5 Jul 2022</span></li>
                            <li class="post-author inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0_.4rem] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[0.8rem] !text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-user pr-[0.2rem] align-[-.05rem] before:content-['\ed6f']"></i><span>By BAN PDM JATIM</span></a></li>
                            <li class="post-likes inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0_.4rem] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[0.8rem] !text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-heart-alt pr-[0.2rem] align-[-.05rem] before:content-['\eb60']"></i>3<span> Likes</span></a></li>
                        </ul>
                        <!-- /.post-meta -->
                    </div>
                    <!-- /.post-header -->
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->
    <section class="wrapper !bg-[#ffffff]">
        <div class="container !pb-[4.5rem] xl:!pb-24 lg:!pb-24 md:!pb-24">
            <div class="flex flex-wrap mx-[-15px]">
                <div class="xl:w-10/12 lg:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
                    <div class="blog single !mt-[-7rem]">
                        <div class="card">
                            <figure class="card-img-top"><img src="{{ asset ('assets/fe/assets/img/photos/b1.jpg') }}" alt="image"></figure>
                            <div class="card-body flex-[1_1_auto] p-[40px] xl:!p-[2.8rem_3rem_2.8rem] lg:!p-[2.8rem_3rem_2.8rem] md:!p-[2.8rem_3rem_2.8rem]">
                                <div class="classic-view">
                                    <article class="post !mb-8">
                                        <div class="relative !mb-5">
                                            <h2 class="h1 !mb-4 !leading-[1.3]">Cras mattis consectetur purus fermentum</h2>
                                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Nullam quis risus eget porta ac consectetur vestibulum.</p>
                                            <p>Donec sed odio dui consectetur adipiscing elit. Etiam adipiscing tincidunt elit, eu convallis felis suscipit ut. Phasellus rhoncus tincidunt auctor. Nullam eu sagittis mauris. Donec non dolor ac elit aliquam tincidunt at at sapien. Aenean tortor libero, condimentum ac laoreet vitae, varius tempor nisi. Duis non arcu vel lectus urna mollis ornare vel eu leo.</p>
                                            <div class="flex flex-wrap mx-[-15px] !mt-3 !mb-10">
                                                <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mt-[30px]">
                                                    <figure class="overflow-hidden translate-y-0 group rounded cursor-dark"><a href="{{ asset ('assets/fe/assets/img/photos/b8-full.jpg') }}" data-glightbox="title: Heading; description: Purus Vulputate Sem Tellus Quam" data-gallery="post"> <img src="{{ asset ('assets/fe/assets/img/photos/b8.jpg') }}" alt="image"></a></figure>
                                                </div>
                                                <!--/column -->
                                                <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mt-[30px]">
                                                    <figure class="overflow-hidden translate-y-0 group rounded cursor-dark"><a href="{{ asset ('assets/fe/assets/img/photos/b9-full.jpg') }}" data-glightbox data-gallery="post"> <img src="{{ asset ('assets/fe/assets/img/photos/b9.jpg') }}" alt="image"></a></figure>
                                                </div>
                                                <!--/column -->
                                                <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mt-[30px]">
                                                    <figure class="overflow-hidden translate-y-0 group rounded cursor-dark"><a href="{{ asset ('assets/fe/assets/img/photos/b10-full.jpg') }}" data-glightbox data-gallery="post"> <img src="{{ asset ('assets/fe/assets/img/photos/b10.jpg') }}" alt="image"></a></figure>
                                                </div>
                                                <!--/column -->
                                                <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mt-[30px]">
                                                    <figure class="overflow-hidden translate-y-0 group rounded cursor-dark"><a href="{{ asset ('assets/fe/assets/img/photos/b11-full.jpg') }}" data-glightbox data-gallery="post"> <img src="{{ asset ('assets/fe/assets/img/photos/b11.jpg') }}" alt="image"></a></figure>
                                                </div>
                                                <!--/column -->
                                            </div>
                                            <!-- /.row -->
                                            <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec sed odio dui. Nulla vitae elit libero, a pharetra augue. Maecenas faucibus mollis interdum. Donec id elit non mi porta gravida at eget metus. Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna.</p>
                                            <blockquote class="border-l-[#3f78e0] !leading-[1.7] font-medium !pl-4 border-l-[0.15rem] border-solid !text-[1rem] !my-8">
                                                <p>Sed posuere consectetur est at lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mollis, est non commodo luctus, nisi erat porttitor ligula lacinia odio sem nec elit purus.</p>
                                                <footer class="!text-[0.6rem] !text-[#aab0bc] !mb-4 before:content-['\2014\a0'] font-bold uppercase !tracking-[0.02rem] !mt-0">Very important person</footer>
                                            </blockquote>
                                            <h3 class="h2 !mb-4">Sit Vulputate Bibendum Purus</h3>
                                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vestibulum id ligula porta felis euismod semper.</p>
                                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis. Donec id elit non mi porta gravida at eget metus. Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                                        </div>
                                        <!-- /.post-content -->
                                        <div class="post-footer xl:!flex xl:!flex-row xl:!justify-between lg:!flex lg:!flex-row lg:!justify-between md:!flex md:!flex-row md:!justify-between !items-center !mt-8">
                                            <div>
                                                <ul class="pl-0 list-none tag-list  !mb-0">
                                                    <li class="!mt-0 !mb-[0.45rem] !mr-[0.2rem] inline-block"><a href="#" class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic before:content-['#'] before:font-normal before:!pr-[0.2rem]">Still Life</a></li>
                                                    <li class="!mt-0 !mb-[0.45rem] !mr-[0.2rem] inline-block"><a href="#" class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic before:content-['#'] before:font-normal before:!pr-[0.2rem]">Urban</a></li>
                                                    <li class="!mt-0 !mb-[0.45rem] !mr-[0.2rem] inline-block"><a href="#" class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic before:content-['#'] before:font-normal before:!pr-[0.2rem]">Nature</a></li>
                                                </ul>
                                            </div>
                                            <div class="!mb-0 xl:!mb-2 lg:!mb-2 md:!mb-2">
                                                <div class="dropdown share-dropdown btn-group">
                                                    <button class="btn btn-sm btn-red !text-white !bg-[#e2626b] border-[#e2626b] hover:text-white hover:bg-[#e2626b] hover:!border-[#e2626b]   active:text-white active:bg-[#e2626b] active:border-[#e2626b] disabled:text-white disabled:bg-[#e2626b] disabled:border-[#e2626b] !rounded-[50rem] btn-icon btn-icon-start dropdown-toggle !mb-0 !mr-0 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="uil uil-share-alt !mr-[0.3rem] text-[0.8rem] before:content-['\ecb0']"></i> Share </button>
                                                    <div class="dropdown-menu !shadow-[rgba(30,34,40,0.06)_0px_0px_25px_0px]">
                                                        <a class="dropdown-item text-[0.7rem] !p-[.25rem_1.15rem]" href="#"><i class="uil uil-twitter w-4 text-[0.8rem] pr-[0.4rem] align-[-.1rem] before:content-['\ed59']"></i>Twitter</a>
                                                        <a class="dropdown-item text-[0.7rem] !p-[.25rem_1.15rem]" href="#"><i class="uil uil-facebook-f w-4 text-[0.8rem] pr-[0.4rem] align-[-.1rem] before:content-['\eae2']"></i>Facebook</a>
                                                        <a class="dropdown-item text-[0.7rem] !p-[.25rem_1.15rem]" href="#"><i class="uil uil-linkedin w-4 text-[0.8rem] pr-[0.4rem] align-[-.1rem] before:content-['\ebd1']"></i>Linkedin</a>
                                                    </div>
                                                    <!--/.dropdown-menu -->
                                                </div>
                                                <!--/.share-dropdown -->
                                            </div>
                                        </div>
                                        <!-- /.post-footer -->
                                    </article>
                                    <!-- /.post -->
                                </div>
                                <!-- /.classic-view -->

                                <!-- /.author-info -->
                                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Maecenas faucibus mollis interdum. Fusce dapibus, tellus ac. Maecenas faucibus mollis interdum.</p>

                                <nav class="nav social">
                                    <a class=" text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class=" text-[1rem] !text-[#5daed5] before:content-['\ed59'] uil uil-twitter"></i></a>
                                    <a class=" text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class=" text-[1rem] !text-[#4470cf] before:content-['\eae2'] uil uil-facebook-f"></i></a>
                                    <a class=" text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class=" text-[1rem] !text-[#e94d88] before:content-['\eaa2'] uil uil-dribbble"></i></a>
                                    <a class=" text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class=" text-[1rem] !text-[#d53581] before:content-['\eb9c'] uil uil-instagram"></i></a>
                                    <a class=" text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="#"><i class=" text-[1rem] !text-[#c8312b] before:content-['\edb5'] uil uil-youtube"></i></a>
                                </nav>
                                <!-- /.social -->
                                <hr>
                                <h3 class="!mb-6">You Might Also Like</h3>
                                <div class="swiper-container blog grid-view !mb-24 relative z-10" data-margin="30" data-dots="true" data-items-md="2" data-items-xs="1">
                                    <div class="swiper">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <article>
                                                    <figure class="overlay overlay-1 hover-scale group rounded !mb-5"><a href="#"> <img class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105" src="{{ asset ('assets/fe/assets/img/photos/b4.jpg') }}" alt="image"></a>
                                                        <figcaption class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                                            <h5 class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">Read More</h5>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="post-header !mb-[.9rem]">
                                                        <div class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                            <a href="#" class="hover" rel="category">Coding</a>
                                                        </div>
                                                        <!-- /.post-category -->
                                                        <h2 class="post-title h3 !mt-1 !mb-3"><a class="!text-[#343f52] hover:!text-[#3f78e0]" href="./blog-post.html">Ligula tristique quis risus</a></h2>
                                                    </div>
                                                    <!-- /.post-header -->
                                                    <div class="post-footer">
                                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none  !mb-0">
                                                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>14 Apr 2022</span></li>
                                                            <li class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>4</a></li>
                                                        </ul>
                                                        <!-- /.post-meta -->
                                                    </div>
                                                    <!-- /.post-footer -->
                                                </article>
                                                <!-- /article -->
                                            </div>
                                            <!--/.swiper-slide -->
                                            <div class="swiper-slide">
                                                <article>
                                                    <figure class="overlay overlay-1 hover-scale group rounded !mb-5"><a href="#"> <img class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105" src="{{ asset ('assets/fe/assets/img/photos/b5.jpg') }}" alt="image"></a>
                                                        <figcaption class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                                            <h5 class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">Read More</h5>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="post-header !mb-[.9rem]">
                                                        <div class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                            <a href="#" class="hover" rel="category">Workspace</a>
                                                        </div>
                                                        <!-- /.post-category -->
                                                        <h2 class="post-title h3 !mt-1 !mb-3"><a class="!text-[#343f52] hover:!text-[#3f78e0]" href="./blog-post.html">Nullam id dolor elit id nibh</a></h2>
                                                    </div>
                                                    <!-- /.post-header -->
                                                    <div class="post-footer">
                                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none  !mb-0">
                                                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>29 Mar 2022</span></li>
                                                            <li class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>3</a></li>
                                                        </ul>
                                                        <!-- /.post-meta -->
                                                    </div>
                                                    <!-- /.post-footer -->
                                                </article>
                                                <!-- /article -->
                                            </div>
                                            <!--/.swiper-slide -->
                                            <div class="swiper-slide">
                                                <article>
                                                    <figure class="overlay overlay-1 hover-scale group rounded !mb-5"><a href="#"> <img class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105" src="{{ asset ('assets/fe/assets/img/photos/b6.jpg') }}" alt="image"></a>
                                                        <figcaption class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                                            <h5 class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">Read More</h5>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="post-header !mb-[.9rem]">
                                                        <div class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                            <a href="#" class="hover" rel="category">Meeting</a>
                                                        </div>
                                                        <!-- /.post-category -->
                                                        <h2 class="post-title h3 !mt-1 !mb-3"><a class="!text-[#343f52] hover:!text-[#3f78e0]" href="./blog-post.html">Ultricies fusce porta elit</a></h2>
                                                    </div>
                                                    <!-- /.post-header -->
                                                    <div class="post-footer">
                                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none  !mb-0">
                                                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>26 Feb 2022</span></li>
                                                            <li class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>6</a></li>
                                                        </ul>
                                                        <!-- /.post-meta -->
                                                    </div>
                                                    <!-- /.post-footer -->
                                                </article>
                                                <!-- /article -->
                                            </div>
                                            <!--/.swiper-slide -->
                                            <div class="swiper-slide">
                                                <article>
                                                    <figure class="overlay overlay-1 hover-scale group rounded !mb-5"><a href="#"> <img class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105" src="{{ asset ('assets/fe/assets/img/photos/b7.jpg') }}" alt="image"></a>
                                                        <figcaption class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                                            <h5 class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">Read More</h5>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="post-header !mb-[.9rem]">
                                                        <div class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                            <a href="#" class="hover" rel="category">Business Tips</a>
                                                        </div>
                                                        <!-- /.post-category -->
                                                        <h2 class="post-title h3 !mt-1 !mb-3"><a class="!text-[#343f52] hover:!text-[#3f78e0]" href="./blog-post.html">Morbi leo risus porta eget</a></h2>
                                                    </div>
                                                    <div class="post-footer">
                                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none  !mb-0">
                                                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>7 Jan 2022</span></li>
                                                            <li class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]"><a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]" href="#"><i class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>2</a></li>
                                                        </ul>
                                                        <!-- /.post-meta -->
                                                    </div>
                                                    <!-- /.post-footer -->
                                                </article>
                                                <!-- /article -->
                                            </div>
                                            <!--/.swiper-slide -->
                                        </div>
                                        <!--/.swiper-wrapper -->
                                    </div>
                                    <!-- /.swiper -->
                                </div>
                                <!-- /.swiper-container -->
                                <hr>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.blog -->
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->
@endsection

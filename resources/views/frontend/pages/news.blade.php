@extends('frontend.layout.app')
@section('content')
    <div class="grow shrink-0">
        <div class="wrapper !bg-[#ffffff]">
            <div class="container py-[4.5rem] xl:!py-24 lg:!py-24 md:!py-24">
                <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
                    <div class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full xl:order-2 lg:order-2">
                        <div class="blog classic-view">
                            <article class="post !mb-8">
                                <figure class="!mb-4">
                                    <a href="#">
                                        <img src="{{ asset('assets/fe/assets/img/photos/b1.jpg') }}" alt="News 1" class="max-w-full h-auto rounded-[.4rem]">
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <h2 class="!text-[1.25rem] font-semibold"><a href="#">Sample News Title One</a></h2>
                                    <p class="lead !text-[1rem]">Short intro text for the first news post. Use real data or loop posts from controller when available.</p>
                                    <a href="#" class="btn btn-soft-purple !rounded-[50rem] !mt-3">Read more</a>
                                </div>
                            </article>

                            <article class="post !mb-8">
                                <figure class="!mb-4">
                                    <a href="#">
                                        <img src="{{ asset('assets/fe/assets/img/photos/b2.jpg') }}" alt="News 2" class="max-w-full h-auto rounded-[.4rem]">
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <h2 class="!text-[1.25rem] font-semibold"><a href="#">Sample News Title Two</a></h2>
                                    <p class="lead !text-[1rem]">Short intro text for the second news post. Replace with dynamic content later.</p>
                                    <a href="#" class="btn btn-soft-purple !rounded-[50rem] !mt-3">Read more</a>
                                </div>
                            </article>

                            <article class="post !mb-8">
                                <figure class="!mb-4">
                                    <a href="#">
                                        <img src="{{ asset('assets/fe/assets/img/photos/b2.jpg') }}" alt="News 3" class="max-w-full h-auto rounded-[.4rem]">
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <h2 class="!text-[1.25rem] font-semibold"><a href="#">Sample News Title Three</a></h2>
                                    <p class="lead !text-[1rem]">Short intro text for the third news post.</p>
                                    <a href="#" class="btn btn-soft-purple !rounded-[50rem] !mt-3">Read more</a>
                                </div>
                            </article>
                        </div>


                        <nav class="flex" aria-label="pagination">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>

                    <aside class="xl:w-4/12 lg:w-4/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full sidebar !mt-8 xl:!mt-6">
                        <div class="widget">
                            <form action="{{ url('/news') }}" method="GET" class="search-form relative">
                                <label for="q" class="sr-only">Search</label>
                                <input id="q" name="q" type="search" placeholder="Search posts..." class="form-input w-full px-4 py-2 rounded border">
                                <button type="submit" class="btn btn-primary !mt-3 w-full">Search</button>
                            </form>
                        </div>

                        <div class="widget !mt-[40px]">
                            <h4 class="widget-title !mb-3">About Us</h4>
                            <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum. Nulla vitae elit libero, a pharetra augue.</p>
                            <nav class="nav social">
                                <a class="text-[1rem] mr-3" href="#"><i class="uil uil-twitter"></i></a>
                                <a class="text-[1rem] mr-3" href="#"><i class="uil uil-facebook-f"></i></a>
                                <a class="text-[1rem]" href="#"><i class="uil uil-instagram"></i></a>
                            </nav>
                        </div>

                        <div class="widget !mt-[40px]">
                            <h4 class="widget-title !mb-3">Popular Posts</h4>
                            <ul class="m-0 p-0 list-none">
                                <li class="mb-4 flex">
                                    <img src="{{ asset('assets/fe/assets/img/photos/news_thumb1.jpg') }}" alt="thumb" class="w-16 h-12 object-cover rounded mr-3">
                                    <a href="#" class="text-sm">Popular post title one</a>
                                </li>
                                <li class="mb-4 flex">
                                    <img src="{{ asset('assets/fe/assets/img/photos/news_thumb2.jpg') }}" alt="thumb" class="w-16 h-12 object-cover rounded mr-3">
                                    <a href="#" class="text-sm">Popular post title two</a>
                                </li>
                            </ul>
                        </div>

                        <div class="widget !mt-[40px]">
                            <h4 class="widget-title !mb-3">Categories</h4>
                            <ul class="pl-0 list-none bullet-primary">
                                <li class="mb-2"><a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">Teamwork (21)</a></li>
                                <li class="mb-2"><a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">Ideas (19)</a></li>
                                <li class="mb-2"><a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">Workspace (16)</a></li>
                            </ul>
                        </div>

                        <div class="widget !mt-[40px]">
                            <h4 class="widget-title !mb-3">Archive</h4>
                            <ul class="pl-0 list-none bullet-primary">
                                <li class="mb-2"><a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">February 2019</a></li>
                                <li class="mb-2"><a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">January 2019</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection

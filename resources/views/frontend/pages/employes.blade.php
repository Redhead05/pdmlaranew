@extends('frontend.layout.app')
@section('content')
        <section class="wrapper !bg-[#ffffff] ">
            <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
                <div class="flex flex-wrap mx-[-15px] !mb-3">
                    <div class="md:w-10/12 lg:w-full xl:w-10/12 xxl:w-9/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !text-center">
                        <h2 class="!text-[.75rem] uppercase !text-[#aab0bc] !mb-3 !tracking-[0.02rem] !leading-[1.35]">Our Team</h2>
                        <h3 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-7 lg:!px-36 xl:!px-32">Think unique and be innovative. Make a difference with Sandbox.</h3>
                    </div>
                    <!--/column -->
                </div>
                <!--/.row -->
                <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t1.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t1@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Coriss Ambady</h4>
                                    <p class="!mb-0 text-[.85rem]">Financial Analyst</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <!--/column -->
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#fcf0f1] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t2.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t2@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Cory Zamora</h4>
                                    <p class="!mb-0 text-[.85rem]">Marketing Specialist</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <!--/column -->
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#edf9f6] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t3.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t3@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Nikolas Brooten</h4>
                                    <p class="!mb-0 text-[.85rem]">Sales Manager</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <!--/column -->
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>

                    <!--/column -->

                </div>
                <hr>
                <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                    <div class="md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:!px-[25px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-[40px] max-md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#f6f3f9] rellax xl:block lg:block md:block absolute" data-rellax-speed="0" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top"><img class="max-w-full h-auto" src="{{  asset('assets/fe/assets/img/avatars/t4.jpg')}}" srcset="{{  asset('assets/fe/assets/img/avatars/t4@2x.jpg')}} 2x" alt="image"></figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">Jackie Sanders</h4>
                                    <p class="!mb-0">Investment Planner</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /div -->
                    </div>
                <!--/.row -->
                </div>
            </div>
        </section>
        <!-- /section -->
@endsection

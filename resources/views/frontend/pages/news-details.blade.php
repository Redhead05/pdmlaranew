@extends('frontend.layout.app')
@section('content')
    <section class="wrapper !bg-[#edf2fc]">
        <div class="container pt-10 pb-36 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] xl:pb-40 lg:pb-40 md:pb-40 !text-center">
            <div class="flex flex-wrap mx-[-15px]">
                <div class="md:w-10/12 lg:w-10/12 xl:w-8/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
                    <div class="post-header !mb-[.9rem]">
                        <div class="inline-flex uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] !mb-[0.4rem]  text-line relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                            <a href="#" class="hover" rel="category">{{ $news->category->name ?? 'Uncategorized' }}</a>
                        </div>
                        <h1 class="!text-[calc(1.365rem_+_1.38vw)] font-bold !leading-[1.2] xl:!text-[2.4rem] !mb-4">{{ $news->title }}</h1>
                        <ul class="!text-[0.8rem] !text-[#aab0bc] m-0 p-0 list-none !mb-5">
                            <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem]"></i><span>{{ $news->created_at->format('j M Y') }}</span></li>
                            <li class="post-author inline-block before:content-['']"><a class="!text-[0.8rem] !text-[#aab0bc] hover:!text-[#3f78e0]" href="#"><span>By {{ $news->created_by ? 'User #'.$news->created_by : 'Admin' }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper !bg-[#ffffff]">
        <div class="container !pb-[4.5rem] xl:!pb-24 lg:!pb-24 md:!pb-24">
            <div class="flex flex-wrap mx-[-15px]">
                <div class="xl:w-10/12 lg:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto">
                    <div class="blog single !mt-[-7rem]">
                        <div class="card">
                            <figure class="card-img-top">
                                <img src="{{ $news->detail && $news->detail->thumbnail ? asset('storage/' . $news->detail->thumbnail) : asset('assets/fe/assets/img/photos/b1.jpg') }}" alt="{{ $news->title }}"
                                >
                            </figure>
                            <div class="card-body flex-[1_1_auto] p-[40px]">
                                <div class="classic-view">
                                    <article class="post !mb-8">
                                        <div class="relative !mb-5">
                                            <h2 class="h1 !mb-4 !leading-[1.3]">{{ $news->title }}</h2>
                                            {!! $news->detail->description ?? '' !!}
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('frontend.layout.app')
@section('content')
    <div class="grow shrink-0">
        <div class="wrapper !bg-[#ffffff]">
            <div class="container py-[4.5rem] xl:!py-24 lg:!py-24 md:!py-24">
                <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
                    <div class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full xl:order-2 lg:order-2">
                        <div class="blog classic-view">
                            @foreach($news as $item)
                                <article class="post !mb-8">
                                    <figure class="!mb-4">
                                        <a href="{{ route('frontend.pages.news-details', $item->slug) }}">
                                            <img
                                                src="{{ $item->detail && $item->detail->thumbnail ? asset('storage/' . $item->detail->thumbnail) : asset('assets/fe/assets/img/photos/b1.jpg') }}"
                                                alt="{{ $item->title }}"
                                                class="max-w-full h-auto rounded-[.4rem]">
                                        </a>
                                    </figure>
                                    <div class="post-content">
                                        <h2 class="!text-[1.25rem] font-semibold">
                                            <a href="{{ route('frontend.pages.news-details', $item->slug) }}">{{ $item->title }}</a>
                                        </h2>
                                        <p class="lead !text-[1rem]">
                                            {!! \Illuminate\Support\Str::limit(strip_tags($item->detail->description ?? ''), 150) !!}
                                        </p>
                                        <a href="{{ route('frontend.pages.news-details', $item->slug) }}" class="btn btn-soft-purple !rounded-[50rem] !mt-3">Read more</a>
                                    </div>
                                </article>
                            @endforeach

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
{{--                                <button type="submit" class="btn btn-primary !mt-3 w-full">Search</button>--}}
                            </form>
                        </div>

                        <div class="widget !mt-[40px]">
                            <h4 class="widget-title !mb-3">Categories</h4>
                            <ul class="pl-0 list-none bullet-primary">
                                @foreach($categories as $cat)
                                    <li class="mb-2">
                                        <a
                                            class="{{ request('category') == $cat->id ? '!text-[#3f78e0]' : '!text-[#60697b]' }} hover:!text-[#3f78e0]"
                                            href="{{ route('frontend.pages.news', ['category' => $cat->id]) }}">
                                            {{ $cat->name }} ({{ $cat->news_count }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection

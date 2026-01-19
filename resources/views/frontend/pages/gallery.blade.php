@extends('frontend.layout.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

    <style>
        .thumb {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
            border-radius: 0.5rem;
        }
        .thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .play-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .play-overlay .triangle {
            width: 0;
            height: 0;
            border-left: 22px solid rgba(255,255,255,0.95);
            border-top: 14px solid transparent;
            border-bottom: 14px solid transparent;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }
        .glightbox-clean .gslide .gimg,
        .glightbox-clean .gslide img,
        .gslide .gimg,
        .gslide img {
            display: block !important;
            max-width: 100% !important;
            height: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        .glightbox-container,
        .glightbox-clean {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 999999 !important;
        }

        /* readable caption */
        .gdesc, .gslide .gdesc {
            display: block !important;
            color: #fff !important;
            background: rgba(0,0,0,0.35) !important;
            padding: 6px 10px !important;
            border-radius: 6px !important;
        }
    </style>
    @php use Illuminate\Support\Str; @endphp

    <div class="grow shrink-0">
        <div class="wrapper !bg-[#ffffff] pt-6 pb-2 rounded-xl border-gray-200 overflow-hidden">
            @if ($galleries->isEmpty())
                <p class="p-4 text-center text-sm text-gray-600">No gallery items found.</p>
            @else
                <div class="grid gap-4 justify-center" style="grid-template-columns: repeat(4, 200px);">
                    @foreach ($galleries as $gallery)
                        @php
                            $isVideo = optional($gallery->category)->name === 'video';
                            $url = $gallery->image_url; // gunakan accessor
                            $thumb = $url;
                            $isYoutube = Str::contains($url, 'youtube.com') || Str::contains($url, 'youtu.be');
                            $dataSource = $isVideo ? ($isYoutube ? 'youtube' : 'html5') : 'image';
                        @endphp

                        @if (! $isVideo)
                            <a href="{{ $url }}"
                               class="glightbox"
                               data-gallery="mixedGallery"
                               data-type="image">
                                <div class="thumb">
                                    <img src="{{ $thumb }}" alt="{{ $gallery->title }}" loading="lazy" />
                                </div>
                            </a>
                        @else
                            <a href="{{ $url }}"
                               class="glightbox"
                               data-gallery="mixedGallery"
                               data-type="video"
                               data-source="{{ $dataSource }}">
                                <div class="thumb">
                                    <img src="{{ $thumb }}" alt="{{ $gallery->title }}" loading="lazy" />
                                    <div class="play-overlay" aria-hidden="true">
                                        <div class="triangle"></div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>

                <nav class="mt-6 flex items-center justify-center space-x-2" aria-label="Pagination">
                    {{ $galleries->onEachSide(1)->withQueryString()->links() }}
                </nav>
            @endif

            <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const lightbox = GLightbox({
                        selector: '.glightbox',
                        touchNavigation: true,
                        loop: true,
                        autoplayVideos: false,
                        descPosition: 'bottom',
                        plyr: { css: 'https://cdn.plyr.io/3.6.8/plyr.css', js: 'https://cdn.plyr.io/3.6.8/plyr.min.js' }
                    });

                    setTimeout(function () {
                        const container = document.querySelector('.glightbox-container');
                        if (container && container.parentElement !== document.body) {
                            document.body.appendChild(container);
                            container.style.position = 'fixed';
                            container.style.zIndex = '999999';
                            container.style.top = '0';
                            container.style.left = '0';
                            container.style.width = '100%';
                            container.style.height = '100%';
                        }
                    }, 50);
                });
            </script>
        </div>
    </div>
@endsection

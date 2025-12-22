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
    <div class="grow shrink-0">
        <div class="wrapper !bg-[#ffffff] pt-6 pb-2 rounded-xl border-gray-200 overflow-hidden">
            @php
                $countImages = 12;
                $countVideos = 4;

                $items = [];

                for ($i = 0; $i < $countImages; $i++) {
                    $seed = substr(md5(uniqid((string)$i, true)), 0, 8);
                    $thumb = "https://picsum.photos/seed/{$seed}/200/200";
                    $full  = "https://picsum.photos/seed/{$seed}/1200/800";
                    $items[] = [
                        'type' => 'image',
                        'thumb' => $thumb,
                        'href' => $full,
                        'desc' => "Random image #" . ($i + 1)
                    ];
                }

                $youtubeIds = ['dQw4w9WgXcQ','ysz5S6PUM-U']; // replace with your IDs
                foreach ($youtubeIds as $idx => $id) {
                    $thumb = "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
                    $href = "https://www.youtube.com/watch?v={$id}";
                    $items[] = [
                        'type' => 'video',
                        'thumb' => $thumb,
                        'href' => $href,
                    ];
                }

                $mp4s = [
                    'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4',
                    'https://samplelib.com/lib/preview/mp4/sample-5s.mp4'
                ];
                foreach ($mp4s as $idx => $mp4) {
                    // simple thumbnail fallback (use a static image or generate one)
                    $thumb = "https://picsum.photos/seed/mp4{$idx}/200/200";
                    $items[] = [
                        'type' => 'video',
                        'thumb' => $thumb,
                        'href' => $mp4,
                    ];
                }

                shuffle($items);
            @endphp

            <div class="grid gap-4 justify-center" style="grid-template-columns: repeat(4, 200px);">
                @foreach ($items as $index => $item)
                    @if ($item['type'] === 'image')
                        <a href="{{ $item['href'] }}"
                           class="glightbox"
                           data-gallery="mixedGallery"
                           data-type="image">
                            <div class="thumb">
                                <img src="{{ $item['thumb'] }}" alt="Photo {{ $index + 1 }}" loading="lazy" />
                            </div>
                        </a>
                    @else
                        <a href="{{ $item['href'] }}"
                           class="glightbox"
                           data-gallery="mixedGallery"
                           data-type="video"
                           data-source="html5">
                            <div class="thumb">
                                <img src="{{ $item['thumb'] }}" alt="Video {{ $index + 1 }}" loading="lazy" />
                                <div class="play-overlay" aria-hidden="true">
                                    <div class="triangle"></div>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <nav class="mt-6 flex items-center justify-center space-x-2" aria-label="Pagination">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>

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

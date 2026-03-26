{!! App\Libraries\Asset::push('js', 'news') !!}

@if ($global_news)
    <div class="sidebar-widget">

        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-danger bg-opacity-10 text-danger rounded p-1 d-flex align-items-center justify-content-center"
                    style="width: 28px; height: 28px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" />
                        <path d="M8 8l4 0" />
                        <path d="M8 12l4 0" />
                        <path d="M8 16l4 0" />
                    </svg>
                </div>
                <h6 class="sidebar-title">Berita Terkini</h6>
            </div>
        </div>

        <div class="sidebar-body p-0">
            <div id="announcements-ticker" style="overflow: hidden;">
                <div class="general-item-list m-0 p-0">
                    @foreach ($global_news as $news)
                        <a href="{{ asset('news/' . $news->id) }}" class="news-item-sidebar">
                            <div class="news-date-small flex-shrink-0">
                                <span
                                    class="day">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('d') }}</span>
                                <span
                                    class="month">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('M') }}</span>
                            </div>
                            <div class="news-info">
                                <div class="text-dark fw-bold small mb-1"
                                    style="line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $news->title }}
                                </div>
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    {{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('Y') }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="p-3 border-top bg-light">
                <a href="/news" class="btn btn-outline-danger btn-sm w-100 fw-bold" style="font-size: 0.75rem;">
                    LIHAT SEMUA
                </a>
            </div>
        </div>
    </div>
@endif

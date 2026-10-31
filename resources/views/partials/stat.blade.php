<div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="icon bg-{{ $color }}-subtle text-{{ $color }}"><i class="bi {{ $icon }}"></i></div>
            <div>
                <div class="text-muted small">{{ $label }}</div>
                <div class="fs-4 fw-bold lh-sm">{{ $value }}</div>
                @if (! empty($url))
                    <a href="{{ $url }}" class="small stretched-link text-decoration-none">Lihat detail</a>
                @endif
            </div>
        </div>
    </div>
</div>
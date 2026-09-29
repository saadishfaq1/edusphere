{{-- {{ dd(session('locale'), app()->getLocale()) }} --}}
<header class="app-header">
    <div class="header-inner">
        <button class="menu-toggle" data-toggle="sidebar" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <div class="header-search d-none d-md-block position-relative">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control" id="global-search-input" placeholder="{{ __('app.search_placeholder') }}" autocomplete="off">
            <div id="global-search-results" class="dropdown-menu shadow" style="display:none; width: 320px; max-height: 350px; overflow-y: auto;"></div>
        </div>

        <div class="header-actions">
            <div class="dropdown">
                <button class="header-action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-translate"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('locale.switch', 'en') }}">English</a>
                    <a class="dropdown-item {{ app()->getLocale() === 'ur' ? 'active' : '' }}" href="{{ route('locale.switch', 'ur') }}">اردو</a>
                </div>
            </div>

            <button class="header-action-btn" data-toggle="theme" type="button" aria-label="Toggle theme">
                <i class="bi bi-moon-stars"></i>
            </button>

            <div class="dropdown">
                <button class="header-action-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if (($unreadCount ?? 0) > 0)
                        <span class="action-dot"></span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                    <div class="notif-header">
                        <h2 class="mb-0 h6">{{ __('app.notifications') }}</h2>
                    </div>
                    <div class="notif-list">
                        @forelse (($notifications ?? []) as $notification)
                            <a href="{{ $notification->link ?? '#' }}" class="notif-item text-decoration-none text-reset d-block">
                                <div class="fw-semibold fs-13">{{ $notification->title }}</div>
                                <div class="text-muted fs-12">{{ $notification->message }}</div>
                                <div class="fs-11 text-muted-soft mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4 fs-13">
                                {{ __('app.no_notifications') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar avatar-sm">
                        @if (auth()->user()->photo_path)
                            <img src="{{ asset('storage/'.auth()->user()->photo_path) }}" alt="{{ auth()->user()->name }}">
                        @else
                            <i class="bi bi-person-circle fs-4"></i>
                        @endif
                    </span>
                    <span class="profile-info">
                        <span class="name d-block">{{ auth()->user()->name }}</span>
                        <span class="role">{{ auth()->user()->getRoleNames()->first() }}</span>
                    </span>
                    <i class="bi bi-chevron-down ms-1 fs-12"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <div class="profile-head">
                        <div>
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <div class="fs-12 text-muted">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <ul>
                        <li><a href="{{ route('my-profile') }}"><i class="bi bi-person"></i> {{ __('app.my_profile') }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start text-danger">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('app.sign_out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
(function () {
    const input = document.getElementById('global-search-input');
    const results = document.getElementById('global-search-results');
    let debounce;

    input.addEventListener('input', function () {
        clearTimeout(debounce);
        const q = this.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            return;
        }

        debounce = setTimeout(() => {
            fetch('{{ route('search.index') }}?q=' + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        results.innerHTML = '<div class="p-3 text-muted text-center fs-13">{{ __('app.no_results') }}</div>';
                    } else {
                        results.innerHTML = data.map(item => `
                            <a href="${item.url}" class="dropdown-item d-flex align-items-center gap-2 py-2">
                                <i class="bi ${item.icon} text-primary"></i>
                                <div>
                                    <div class="fw-semibold fs-13">${item.title}</div>
                                    <div class="text-muted fs-12">${item.type} · ${item.subtitle}</div>
                                </div>
                            </a>
                        `).join('');
                    }
                    results.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
        }
    });
})();
</script>
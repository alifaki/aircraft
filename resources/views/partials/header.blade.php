<div class="d-flex align-items-center gap-3">
    <button id="av-menu-toggle" class="av-mobile-toggle" type="button" aria-label="Open navigation" aria-controls="av-sidebar" aria-expanded="false">☰</button>
    <div><strong>Operations control</strong><div class="meta">{{ now('UTC')->format('D, d M Y') }} · UTC</div></div>
</div>
<div class="d-flex align-items-center gap-3">
    <span class="av-pill d-none d-sm-inline">SAFETY FIRST</span>
    <span class="av-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
    <span class="d-none d-md-inline av-account-name">{{ auth()->user()->staffs?->first_name ?? auth()->user()->username }}</span>
    <a class="av-btn av-btn-outline" href="{{ route('logout') }}">Sign out</a>
</div>

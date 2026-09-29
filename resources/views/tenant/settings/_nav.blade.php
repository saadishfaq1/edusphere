<div class="list-group">
    <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
        <i class="bx bx-buildings me-2"></i> School Profile
    </a>
    <a href="{{ route('settings.brand') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.brand') ? 'active' : '' }}">
        <i class="bx bx-palette me-2"></i> Brand Settings
    </a>
    <a href="{{ route('settings.users') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.users') ? 'active' : '' }}">
        <i class="bx bx-user-circle me-2"></i> Users & Roles
    </a>
</div>


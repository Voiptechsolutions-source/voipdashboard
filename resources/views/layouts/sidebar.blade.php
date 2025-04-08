<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    @auth
      @php
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $role = $user->role;
        $permissions = $isSuperAdmin ? collect(['all' => true]) : ($role ? $role->permissions->pluck('pivot', 'page_name') : collect());
      @endphp

      <!-- Dashboard -->
      @if($isSuperAdmin || ($permissions->has('dashboard') && $permissions['dashboard']->can_view))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
          </a>
        </li>
      @endif

      <!-- Leads -->
      @if($isSuperAdmin || ($permissions->has('leads') && $permissions['leads']->can_view))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('leads.index') }}">
            <i class="bi bi-people"></i>
            <span>Lead Data</span>
          </a>
        </li>
      @endif

      <!-- Import Customers -->
      @if($isSuperAdmin || ($permissions->has('import-customers') && $permissions['import-customers']->can_view))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('import.customers.form') }}">
            <i class="bi bi-upload"></i>
            <span>Import Customer Data</span>
          </a>
        </li>
      @endif

      <!-- Customers -->
      @if($isSuperAdmin || ($permissions->has('customers') && $permissions['customers']->can_view))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('converted.leads') }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Customers</span>
          </a>
        </li>
      @endif

      <!-- Support -->
      @if($isSuperAdmin || ($permissions->has('support') && $permissions['support']->can_view))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('support.index') }}">
            <i class="bi bi-life-preserver"></i>
            <span>Support Revenue</span>
          </a>
        </li>
      @endif

      {{-- Roles (Commented Out) --}}
      {{-- @if($isSuperAdmin || ($permissions->has('Roles') && $permissions['Roles']->can_view))
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="bi bi-shield-lock"></i>
            <span>Roles</span>
          </a>
        </li> -->
      @endif --}}

      {{-- Users (Commented Out) --}}
      {{-- @if($isSuperAdmin || ($permissions->has('users') && $permissions['users']->can_view))
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{ route('users.index') }}">
            <i class="bi bi-person-circle"></i>
            <span>Users</span>
          </a>
        </li> -->
      @endif --}}
    @endauth
  </ul>
</aside><!-- End Sidebar -->
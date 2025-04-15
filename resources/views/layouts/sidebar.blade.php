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
      
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
          </a>
        </li>
      

      <!-- Leads -->
      @if($isSuperAdmin || ($permissions->has('leads') && $permissions['leads']->can_view))
        <li class="nav-item {{ request()->is('leads*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('leads.index') }}">
            <i class="bi bi-people"></i>
            <span>Lead Data</span>
          </a>
        </li>
      @endif

      <!-- Import Customers -->
      @if($isSuperAdmin || ($permissions->has('import-customers') && $permissions['import-customers']->can_view))
        <li class="nav-item {{ request()->is('import-customers*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('import.customers.form') }}">
            <i class="bi bi-upload"></i>
            <span>Import Customer Data</span>
          </a>
        </li>
      @endif

      <!-- Customers -->
      @if($isSuperAdmin || ($permissions->has('customers') && $permissions['customers']->can_view))
        <li class="nav-item {{ request()->is('customers*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('converted.leads') }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Customers</span>
          </a>
        </li>
      @endif

      <!-- Support -->
      @if($isSuperAdmin || ($permissions->has('support') && $permissions['support']->can_view))
        <li class="nav-item {{ request()->is('support*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('support.index') }}">
            <i class="bi bi-life-preserver"></i>
            <span>Support Revenue</span>
          </a>
        </li>
      @endif

      {{-- Roles --}}
      @if($isSuperAdmin || ($permissions->has('Roles') && $permissions['Roles']->can_view))
        <li class="nav-item {{ request()->is('roles*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="bi bi-shield-lock"></i>
            <span>Roles</span>
          </a>
        </li>
      @endif

      {{-- Users--}}
      @if($isSuperAdmin || ($permissions->has('users') && $permissions['users']->can_view))
        <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('users.index') }}">
            <i class="bi bi-person-circle"></i>
            <span>Users</span>
          </a>
        </li>
      @endif

      {{-- Email Template--}}
      
        <li class="nav-item {{ request()->is('email-templates*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('email-templates.index') }}">
            <i class="bi bi-person-circle"></i>
            <span>Email Template</span>
          </a>
        </li>

        {{-- Send Email Template--}}
      
        <li class="nav-item {{ request()->is('send-email*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('send-email.form') }}">
            <i class="bi bi-person-circle"></i>
            <span>Send Email</span>
          </a>
        </li>

        {{-- schedule-reminder Template--}}
      
        <li class="nav-item {{ request()->is('schedule-reminder*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('schedule-reminder.form') }}">
            <i class="bi bi-person-circle"></i>
            <span>Schedule Reminder</span>
          </a>
        </li>
      
    @endauth
  </ul>
</aside><!-- End Sidebar -->
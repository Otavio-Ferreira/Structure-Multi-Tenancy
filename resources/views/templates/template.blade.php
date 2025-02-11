<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Multi-Tenancy</title>
  <!-- CSS files -->

  @yield('styles')

  <link href="{{ global_asset('assets/css/tabler-icons.min.css') }}" rel="stylesheet" />
  <link href="{{ global_asset('assets/css/tabler.min.css') }}" rel="stylesheet" />
  <link href="{{ global_asset('assets/css/tabler-payments.min.css') }}" rel="stylesheet" />
  <link href="{{ global_asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet" />
  <link href="{{ global_asset('assets/css/demo.min.css') }}" rel="stylesheet" />
  <link rel="shortcut icon" href="{{ global_asset('assets/img/illustrations/logo-small.svg') }}" type="image/x-icon">


  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
</head>

<body>
  <script src="{{ asset('assets/js/demo-theme.min.js?1684106062') }}"></script>
  <div class="page">
    <!-- Navbar -->
    <header class="navbar navbar-expand-md d-print-none">
      <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
          aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
          <a href="/dashboard">
            <img src="{{ global_asset('assets/img/illustrations/logo.svg') }}" width="110" height="32"
              alt="tabler" class="navbar-brand-image">
          </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
          <div class="d-none d-md-flex me-3">
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Mudar para tema escuro"
              data-bs-toggle="tooltip" data-bs-placement="bottom">
              <i class="ti ti-moon icon"></i>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Mudar para tema claro"
              data-bs-toggle="tooltip" data-bs-placement="bottom">
              <i class="ti ti-sun icon"></i>
            </a>
          </div>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
              aria-label="Open user menu">
              <span class="avatar avatar-sm">
                <i class="ti ti-user icon"></i>
              </span>
              <div class="d-none d-xl-block ps-2">
                <div>{{ Auth::user()->name }}</div>
                <div class="mt-1 fs-6 text-muted">{{ ucfirst(Auth::user()->roles->first()->name) }}</div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <a href="{{ tenant() ? tenant_route_url('users/sair') : route('logout') }}"
                class="dropdown-item">Sair</a>
            </div>
          </div>
        </div>
      </div>
    </header>
    <header class="navbar-expand-md">
      <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
          <div class="container-xl">
            <ul class="navbar-nav">
              <x-navbar.navbar-item route="{{ tenant() ? tenant_route_url('dashboard') : route('home.index') }}"
                title="Dashboard" isActive="{{ request()->routeIs(['home.*']) ? true : false }}" icon="ti-chart-bar">
              </x-navbar.navbar-item>

              @canany('adicionar_tenants')
                <x-navbar.navbar-item route="{{ tenant() ? tenant_route_url('tenant') : route('tenant.index') }}"
                  title="Tenants" isActive="{{ request()->routeIs(['tenant.*']) ? true : false }}"
                  icon="ti-binary-tree-2">
                </x-navbar.navbar-item>
              @endcan

              @canany('adicionar_apps')
                <x-navbar.navbar-item route="{{ tenant() ? tenant_route_url('tenant') : route('apps.index') }}"
                  title="Apps" isActive="{{ request()->routeIs(['apps.*']) ? true : false }}" icon="ti-apps">
                </x-navbar.navbar-item>
              @endcan

              @canany(['adicionar_usuário', 'adicionar_grupo', 'adicionar_permissões'])
                <x-navbar.navbar-item route="" title="Configurações"
                  isActive="{{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? true : false }}"
                  icon="ti-settings">
                  <x-slot:links>
                    @can('adicionar_usuário')
                      <a class="dropdown-item"
                        href="{{ tenant() ? tenant_route_url('usuarios') : route('users.index') }}">Usuários</a>
                    @endcan
                    @can('adicionar_grupo')
                      <a class="dropdown-item"
                        href="{{ tenant() ? tenant_route_url('grupos') : route('roles.index') }}">Grupos</a>
                    @endcan
                  </x-slot:links>
                </x-navbar.navbar-item>
              @endcanany

              {{-- @role('SuperAdmin')
                            <x-navbar.navbar-item route="" title="Configurações" isActive="{{request()->routeIs(['users.*', 'roles.*','permissions.*']) ? true : false}}" icon="ti-settings">
                                <x-slot:links>
                                    <a class="dropdown-item" href="{{route('companies.index')}}">Empresas</a>
                                    <a class="dropdown-item" href="{{route('users.index')}}">Usuários</a>
                                    <a class="dropdown-item" href="{{route('roles.index')}}">Grupos</a>
                                    <a class="dropdown-item" href="{{route('permissions.index')}}">Permissões</a>
                                </x-slot:links>
                            </x-navbar.navbar-item>
                            @endrole --}}
            </ul>
          </div>
        </div>
      </div>
    </header>
    <div class="page-wrapper">

      <div class="container">
        @yield('content')
      </div>

      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item"><a href="" target="_blank" class="link-secondary"
                    rel="noopener">Suport</a></li>
              </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">
                  Copyright &copy; 2024
                  <a href="." class="link-secondary">Araripe Softwares</a>.
                  All rights reserved.
                </li>
                <li class="list-inline-item">
                  <a href="./changelog.html" class="link-secondary" rel="noopener">
                    v1.0.0-beta
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <script src="{{ global_asset('assets/js/jquery-3.5.1.js') }}"></script>
  <script src="{{ global_asset('assets/js/echarts.min.js') }}"></script>
  <script src="{{ global_asset('assets/js/tabler.min.js?1684106062') }}" defer></script>
  <script src="{{ global_asset('assets/js/demo.min.js?1684106062') }}" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  {{-- <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script> --}}
  @include('sweetalert::alert')
  @yield('scripts')

</body>

</html>

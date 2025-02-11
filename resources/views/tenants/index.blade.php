@extends('templates.template')

@section('styles')
@endsection
@section('content')
  <div class="page-header">
    <div class="">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">
            <a href="{{ route('tenant.index') }}">Tenants</a>
          </div>
          <h2 class="page-title">
            Tenants
          </h2>
        </div>
        <div class="col-auto ms-auto">
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    @can('ver_dashboard')
      <div class="">
        <div class="p-2">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add_tenant"
                aria-controls="offcanvasExample">
                Cadastrar Tenant
              </button>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <th>Nome</th>
                  <th>Dominio</th>
                  <th></th>
                  <th></th>
                  {{-- <th>Dominio</th> --}}
                </thead>
                <tbody>
                  @foreach ($tenants as $item_t)
                    <tr>
                      <td>{{ $item_t->name }}</td>
                      <td>{{ $item_t->domain->domain }}</td>
                      <td><a href="http://{{ $item_t->domain->domain }}:8000" class="btn btn-sm btn-green"
                          target="_blank">Link</a></td>
                      <td><button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas"
                          data-bs-target="#apps{{ $item_t->id }}" aria-controls="offcanvasExample">Apps</button></td>
                    </tr>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="apps{{ $item_t->id }}"
                      aria-labelledby="offcanvasExampleLabel">
                      <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Apps de {{ $item_t->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                      </div>
                      <div class="offcanvas-body">
                        <form action="{{ route('app.give') }}" method="post">
                          @csrf
                          @foreach ($apps as $item_a)
                            <div class="mb-3">
                              <label class="form-check form-switch form-switch-3">
                                <input class="form-check-input" type="checkbox" name="apps[]" value="{{ $item_a->id }}"
                                  {{ $item_t->apps->contains('id', $item_a->id) ? 'checked' : '' }}>
                                <span class="form-check-label">{{ $item_a->name }}</span>
                              </label>
                            </div>
                          @endforeach
                          <input type="hidden" name="tenant_id" value="{{ $item_t->id }}">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                      </div>
                    </div>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endcan
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script>
    function verify(value) {
      div = document.getElementById("div");
      if (value == "sim") {
        div.classList.remove("d-none")
      }
      if (value == "nao") {
        div.classList.add("d-none")
      }
    }
  </script>
@endsection

<div class="offcanvas offcanvas-start" tabindex="-1" id="add_app" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Cadastrar App</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form action="{{ route('app.store') }}" method="post">
      @csrf
      @include('components.form-elements.input.input', [
          'title' => 'Nome do app',
          'type' => 'text',
          'class' => 'mb-3',
          'name' => 'name',
          'required' => 'true',
          'placeholder' => 'Adicone um nome para o app',
      ])
      <button type="submit" class="btn btn-success">Enviar</button>
    </form>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add_tenant" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Cadastrar Tenant</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form action="{{ route('tenant.store') }}" method="post">
      @csrf
      @include('components.form-elements.input.input', [
          'title' => 'Nome do tenant',
          'type' => 'text',
          'class' => 'mb-3',
          'name' => 'name',
          'required' => 'true',
          'placeholder' => 'Adicone um nome para o tenant',
      ])
      @include('components.form-elements.input.input', [
          'title' => 'Domínio do tenant',
          'type' => 'text',
          'class' => 'mb-3',
          'name' => 'domain',
          'required' => 'true',
          'placeholder' => 'Adicone o dominio do tenant',
      ])
      {{-- <div>
        <label for="" class="mb-1">Banco exisnte?</label>
        <div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="exist" id="sim" onclick="verify('sim')">
            <label class="form-check-label" for="sim">
              Sim
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="exist" id="nao" checked onclick="verify('nao')">
            <label class="form-check-label" for="nao">
              Não
            </label>
          </div>
        </div>
      </div> --}}

      <div id="div" class="">
        @include('components.form-elements.input.input', [
            'title' => 'Email',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'email',
            'required' => 'true',
            'placeholder' => 'Digite o email do tenant',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Nome do banco de dados',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'tenancy_db_name',
            'required' => 'true',
            'placeholder' => 'Adicone um nome para o banco de dados',
            'value' => 'db_tenant_',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Host do banco de dados',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'tenancy_db_host',
            'required' => 'true',
            'placeholder' => 'Adicone o host do banco de dados',
            'value' => 'localhost',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Nome do usuário do banco',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'tenancy_db_user',
            'required' => 'true',
            'placeholder' => 'Adicone um nome para o usuário do banco',
            'value' => 'phpmyadmin',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Senha do banco de dados',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'tenancy_db_password',
            'required' => 'true',
            'placeholder' => 'Adicone a senha do banco de dados',
            'value' => 'root',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Porta do banco de dados',
            'type' => 'text',
            'class' => 'mb-3',
            'name' => 'tenancy_db_port',
            'required' => 'true',
            'placeholder' => 'Adicone a porta do banco de dados',
            'value' => '3306',
        ])

        <x-form-elements.select.select title="Grupo de permissões" id="role" name="role">
          <x-slot:options>
            <option value="" selected>Selecione</option>
            @foreach ($roles as $role)
              <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </x-slot:options>
        </x-form-elements.select.select>
      </div>
      <button type="submit" class="btn btn-success">Enviar</button>
    </form>
  </div>
</div>

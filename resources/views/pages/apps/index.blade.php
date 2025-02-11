@extends('templates.template')

@section('styles')
@endsection
@section('content')
  <div class="page-header">
    <div class="">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">
            <a href="{{ route('apps.index') }}">Apps</a>
          </div>
          <h2 class="page-title">
            Apps
          </h2>
        </div>
        <div class="col-auto ms-auto">
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    @can('adicionar_apps')
      <div class="row">
        <div class="p-2">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add_app"
                aria-controls="offcanvasExample">
                Cadastrar App
              </button>
            </div>
            <div class="card-body">
              <table class="table table table-vcenter table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <th>Nome</th>
                  <th>Controller</th>
                  <th>Color</th>
                  <th>Status</th>
                  <th width="10%"></th>
                </thead>
                <tbody>
                  @foreach ($apps as $item)
                    <tr>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->controller }}</td>
                      <td>{{ $item->color }}</td>
                      <td>
                        <x-badge.badge class="{{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                          <x-slot:content>
                            {{ $item->status == 1 ? 'Ativo' : 'Inativo' }}
                          </x-slot:content>
                        </x-badge.badge>
                      </td>
                      <td><button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
                          data-bs-target="#apps-edit{{ $item->id }}" aria-controls="offcanvasExample">Editar</button>
                      </td>
                    </tr>

                    <div class="offcanvas offcanvas-start" tabindex="-1" id="apps-edit{{ $item->id }}"
                      aria-labelledby="offcanvasExampleLabel">
                      <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Cadastrar App</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                      </div>
                      <div class="offcanvas-body">
                        <form action="{{ route('apps.update', $item->id) }}" method="post">
                          @csrf
                          @include('components.form-elements.input.input', [
                              'title' => 'Nome do app',
                              'type' => 'text',
                              'class' => 'mb-3',
                              'name' => 'name',
                              'required' => 'true',
                              'placeholder' => 'Adicone um nome para o app',
                              'value' => $item->name,
                          ])
                          @include('components.form-elements.input.input', [
                              'title' => 'Controller do app',
                              'type' => 'text',
                              'class' => 'mb-3',
                              'name' => 'controller',
                              'required' => 'true',
                              'placeholder' => 'Adicone um controller para o app',
                              'value' => $item->controller,
                          ])
                          @include('components.form-elements.input.input', [
                              'title' => 'Cor do app',
                              'type' => 'color',
                              'class' => 'mb-3',
                              'name' => 'color',
                              'required' => 'true',
                              'placeholder' => 'Adicone uma cor para o app',
                              'value' => $item->color,
                          ])
                          <x-form-elements.select.select title="Status" id="status" name="status">
                            <x-slot:options>
                              <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Ativo</option>
                              <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Inativo</option>
                            </x-slot:options>
                          </x-form-elements.select.select>
                          <button type="submit" class="btn btn-success">Enviar</button>
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
    <form action="{{ route('apps.store') }}" method="post">
      @csrf
      @include('components.form-elements.input.input', [
          'title' => 'Nome do app',
          'type' => 'text',
          'class' => 'mb-3',
          'name' => 'name',
          'required' => 'true',
          'placeholder' => 'Adicone um nome para o app',
      ])
      @include('components.form-elements.input.input', [
          'title' => 'Controller do app',
          'type' => 'text',
          'class' => 'mb-3',
          'name' => 'controller',
          'required' => 'true',
          'placeholder' => 'Adicone um controller para o app',
      ])
      @include('components.form-elements.input.input', [
          'title' => 'Cor do app',
          'type' => 'color',
          'class' => 'mb-3',
          'name' => 'color',
          'required' => 'true',
          'placeholder' => 'Adicone uma cor para o app',
      ])
      <x-form-elements.select.select title="Status" id="status" name="status">
        <x-slot:options>
          <option value="1">Ativo</option>
          <option value="0">Inativo</option>
        </x-slot:options>
      </x-form-elements.select.select>
      <button type="submit" class="btn btn-success">Enviar</button>
    </form>
  </div>
</div>

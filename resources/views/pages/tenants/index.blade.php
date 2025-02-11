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
    @can('adicionar_tenants')
      <div class="">
        <div class="">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add_tenant"
                aria-controls="offcanvasExample">
                Cadastrar Tenant
              </button>
            </div>
            <div class="card-body">
              <table class="table table-vcenter table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <th>Nome</th>
                  <th>Dominio</th>
                  <th>Status</th>
                  <th width="10%"></th>
                  <th width="10%"></th>
                  <th width="10%"></th>
                  {{-- <th>Dominio</th> --}}
                </thead>
                <tbody>
                  @foreach ($tenants as $item_t)
                    <tr>
                      <td>{{ $item_t->name }}</td>
                      <td>{{ $item_t->domain->domain }}</td>
                      <td>
                        <x-badge.badge class="{{ $item_t->status == 1 ? 'bg-success' : 'bg-danger' }}">
                          <x-slot:content>
                            {{ $item_t->status == 1 ? 'Ativo' : 'Inativo' }}
                          </x-slot:content>
                        </x-badge.badge>
                      </td>
                      <td><button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
                          data-bs-target="#apps-edit{{ $item_t->id }}" aria-controls="offcanvasExample">Editar</button>
                      </td>
                      <td><button class="btn btn-cyan" type="button" data-bs-toggle="offcanvas"
                          data-bs-target="#apps{{ $item_t->id }}" aria-controls="offcanvasExample">Apps</button></td>
                      <td>
                        <button class="btn btn-danger" data-bs-toggle="modal"
                          data-bs-target="#modal-delete-{{ $item_t->id }}"><i class="ti ti-trash"></i></button>

                        <x-modal.modal-alert route="{{ route('tenant.destroy', $item_t->id) }}"
                          id="modal-delete-{{ $item_t->id }}" class="modal-dialog-centered modal-sm"
                          background="bg-danger" classBody="text-center py-4" title="Excluír tenant'" typeBtnClose="button"
                          classBtnClose="me-auto w-100" textBtnClose="Cancelar" typeBtnSave="submit'"
                          classBtnSave="btn-danger w-100" textBtnSave="Deletar">
                          <x-slot:content>
                            <i class="ti ti-alert-triangle icon icon-lg text-danger"></i>
                            <h3>Tem certeza?</h3>
                            <div class="text-secondary">
                              Você realmente deseja remover esse registro? Não será possível restaurá-lo depois!
                            </div>
                          </x-slot:content>
                        </x-modal.modal-alert>
                      </td>
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

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="apps-edit{{ $item_t->id }}"
                      aria-labelledby="offcanvasExampleLabel">
                      <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Editar {{ $item_t->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                      </div>
                      <div class="offcanvas-body">
                        <form action="{{ route('tenant.update', $item_t->id) }}" method="post">
                          @csrf
                          @include('components.form-elements.input.input', [
                              'title' => 'Nome do tenant',
                              'type' => 'text',
                              'class' => 'mb-3',
                              'name' => 'name',
                              'required' => 'true',
                              'placeholder' => 'Adicone um nome para o tenant',
                              'value' => $item_t->name,
                          ])
                          @include('components.form-elements.input.input', [
                              'title' => 'Domínio do tenant',
                              'type' => 'text',
                              'class' => 'mb-3',
                              'name' => 'domain',
                              'required' => 'true',
                              'placeholder' => 'Adicone o dominio do tenant',
                              'value' => $item_t->domain->domain,
                          ])

                          <div id="div" class="">
                            @include('components.form-elements.input.input', [
                                'title' => 'Nome do banco de dados',
                                'type' => 'text',
                                'class' => 'mb-3',
                                'name' => 'tenancy_db_name',
                                'required' => 'true',
                                'placeholder' => 'Adicone um nome para o banco de dados',
                                'value' => 'db_tenant_',
                                'value' => $item_t->tenancy_db_name,
                            ])
                            @include('components.form-elements.input.input', [
                                'title' => 'Host do banco de dados',
                                'type' => 'text',
                                'class' => 'mb-3 d-none',
                                'name' => 'tenancy_db_host',
                                'required' => 'true',
                                'placeholder' => 'Adicone o host do banco de dados',
                                'value' => 'localhost',
                                'value' => $item_t->tenancy_db_host,
                            ])
                            @include('components.form-elements.input.input', [
                                'title' => 'Nome do usuário do banco',
                                'type' => 'text',
                                'class' => 'mb-3 d-none',
                                'name' => 'tenancy_db_user',
                                'required' => 'true',
                                'placeholder' => 'Adicone um nome para o usuário do banco',
                                'value' => 'phpmyadmin',
                                'value' => $item_t->tenancy_db_user,
                            ])
                            @include('components.form-elements.input.input', [
                                'title' => 'Senha do banco de dados',
                                'type' => 'text',
                                'class' => 'mb-3 d-none',
                                'name' => 'tenancy_db_password',
                                'required' => 'true',
                                'placeholder' => 'Adicone a senha do banco de dados',
                                'value' => 'root',
                                'value' => $item_t->tenancy_db_password,
                            ])
                            @include('components.form-elements.input.input', [
                                'title' => 'Porta do banco de dados',
                                'type' => 'text',
                                'class' => 'mb-3 d-none',
                                'name' => 'tenancy_db_port',
                                'required' => 'true',
                                'placeholder' => 'Adicone a porta do banco de dados',
                                'value' => '3306',
                                'value' => $item_t->tenancy_db_port,
                            ])

                            <x-form-elements.select.select title="Status" id="status" name="status">
                              <x-slot:options>
                                <option value="1" {{ $item_t->status == 1 ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ $item_t->status == 0 ? 'selected' : '' }}>Inativo</option>
                              </x-slot:options>
                            </x-form-elements.select.select>
                          </div>
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
@endsection

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
            'class' => 'mb-3 d-none',
            'name' => 'tenancy_db_host',
            'required' => 'true',
            'placeholder' => 'Adicone o host do banco de dados',
            'value' => 'localhost',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Nome do usuário do banco',
            'type' => 'text',
            'class' => 'mb-3 d-none',
            'name' => 'tenancy_db_user',
            'required' => 'true',
            'placeholder' => 'Adicone um nome para o usuário do banco',
            'value' => 'phpmyadmin',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Senha do banco de dados',
            'type' => 'text',
            'class' => 'mb-3 d-none',
            'name' => 'tenancy_db_password',
            'required' => 'true',
            'placeholder' => 'Adicone a senha do banco de dados',
            'value' => 'root',
        ])
        @include('components.form-elements.input.input', [
            'title' => 'Porta do banco de dados',
            'type' => 'text',
            'class' => 'mb-3 d-none',
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

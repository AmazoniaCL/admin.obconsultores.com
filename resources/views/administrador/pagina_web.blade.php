@extends('layouts.app')

@section('title_content') Configuración Pagina Web @endsection

@section('myScripts') <script src="{{ asset('assets/js/pagina_web.js') }}"></script> @endsection

@section('content')

<div class="section-body">

    <a href="{{ url()->previous() }}"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

    <form class="card" method="POST" action="/administrador/pagina-web" enctype="multipart/form-data">
        @csrf

        <div class="card-body">

            @if (session()->has('update') && session()->has('update') == 1)
                <div class="alert alert-icon alert-success col-12" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Información actualizada correctamente.
                </div>
            @endif

            <h3 class="card-title">Información de la pagina web</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" id="correo">Correo</label>
                        <input type="text" class="form-control" id="correo" name="correo" placeholder="Escriba el correo" value="{{ $config->correo ?? "" }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Escriba la direccion" value="{{ $config->direccion ?? "" }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Telefono</label>
                        <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Escriba el telefono" value="{{ $config->telefono ?? "" }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" id="facebook">Facebook</label>
                        <input type="number" class="form-control" id="facebook" name="facebook" placeholder="Escriba el link de facebook" value="{{ $config->facebook ?? "" }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Twitter</label>
                        <input type="text" class="form-control" name="twitter" id="twitter" placeholder="Escriba el link de twitter" value="{{ $config->twitter ?? "" }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Instagram</label>
                        <input type="text" class="form-control" name="instagram" id="instagram" placeholder="Escriba el link de instagram" value="{{ $config->instagram ?? "" }}">
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
        </div>
    </form>

</div>

@endsection



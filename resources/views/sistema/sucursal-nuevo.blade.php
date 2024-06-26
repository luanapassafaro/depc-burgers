@extends('sistema.plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($sucursal->idsucursal) && $sucursal->idsucursal > 0 ? $sucursal->idsucursal : 0; ?>';
    <?php $globalId = $sucursal->idsucursal ?? "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/sucursales">Sucursales</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/sucursal/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-save" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Eliminar" href="#" class="fa fa-trash" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
@endsection
@section('contenido')
@include('sistema.msg')
<div class="panel-body">
    <form id="form1" method="POST">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            <div class="form-group col-lg-6">
                <label>Nombre: *</label>
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{ $sucursal->nombre }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Dirección: *</label>
                <input type="text" id="txtDireccion" name="txtDireccion" class="form-control" value="{{ $sucursal->direccion }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Telefono: *</label>
                <input type="tel" id="txtTelefono" name="txtTelefono" class="form-control" value="{{ $sucursal->telefono }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Google Maps URL:</label>
                <input type="text" id="txtMapsUrl" name="txtMapsUrl" class="form-control" value="{{ $sucursal->maps_url }}">
            </div>
        </div>
    </form>
</div>
@include('sistema.funciones-form', ['formUrl'=> 'admin/sucursal', 'indexUrl'=>'/admin/sucursales'])
@endsection
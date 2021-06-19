@extends('voyager::master')

@section('page_title', 'Viendo Compras')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-title">
                    <i class="voyager-receipt"></i> Compras
                </h1>
                <a href="{{ route('compras.create') }}" class="btn btn-success btn-add-new">
                    <i class="voyager-plus"></i> <span>Crear</span>
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip()
            let columns = [
                { data: 'id', title: 'id' },
                { data: 'fecha', title: 'Fecha' },
                { data: 'proveedor', title: 'proveedor' },
                { data: 'empleado', title: 'Empleado' },
                { data: 'detalles', title: 'Detalles' },
                { data: 'total', title: 'Total Bs.' },
                { data: 'action', title: 'Acciones', orderable: false, searchable: false },
            ]
            customDataTable("{{ url('admin/compras/ajax/list') }}", columns);
        });
        function changeStatus(data){
            let estados_servicio = data.estados_servicio;
            $('#select-servicios_estado_id').val(estados_servicio[estados_servicio.length -1].estado.id);
            $('#select-empleado_id').val(data.empleado_id);
            $('#etapa_form input[name="servicio_id"]').val(data.id);
            $('#etapa_form input[name="costo"]').val(data.costo);
            $('#etapa_form textarea[name="observaciones"]').val(data.observaciones);
            if(data.empleado_id){
                $('#form-group-empleado_id').fadeOut('fast');
            }else{
                $('#form-group-empleado_id').fadeIn('fast');
            }
        }
        function deleteItem(id){
            let url = '{{ url("admin/compras") }}/'+id;
            $('#delete_form').attr('action', url);
        }
    </script>
@stop

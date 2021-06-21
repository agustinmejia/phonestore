@extends('voyager::master')

@section('page_title', 'Viendo Ventas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-title">
                    <i class="voyager-basket"></i> Ventas
                </h1>
                <a href="{{ route('ventas.create') }}" class="btn btn-success btn-add-new">
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

    @include('partials.modal-delete')
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
                { data: 'cliente', title: 'Cliente' },
                { data: 'garante', title: 'Garante(s)' },
                { data: 'detalles', title: 'Detalles' },
                { data: 'total', title: 'Total Bs.' },
                { data: 'deuda', title: 'Deuda Bs.' },
                { data: 'action', title: 'Acciones', orderable: false, searchable: false },
            ]
            customDataTable("{{ url('admin/ventas/ajax/list') }}", columns);
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
    </script>
@stop

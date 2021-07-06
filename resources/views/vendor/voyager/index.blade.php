@extends('voyager::master')

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <h1 class="page-title" style="padding-left: 0px">
            Bienvenido, {{ Auth::user()->name }}!
        </h1>
        <div class="col-md-12" style="margin-top: 20px">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            @php
                                $pagos = \App\Models\VentasDetallesCuotasPago::where('deleted_at', NULL)->whereDate('created_at', date('Y-m-d'))->get();
                                $registros_caja = \App\Models\RegistrosCaja::where('deleted_at', NULL)->whereDate('created_at', date('Y-m-d'))->get();
                                $ventas = \App\Models\Venta::where('deleted_at', NULL)->whereDate('fecha', date('Y-m-d'))->get();
                                $cuotas = \App\Models\VentasDetallesCuota::where('deleted_at', NULL)->whereDate('fecha', date('Y-m-d'))->where('estado', 'pendiente')->get();
                            @endphp
                            <div class="panel panel-bordered" style="border-left: 5px solid #2E86C1">
                                <div class="panel-body" style="height: 130px">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>Ingresos del día</h5>
                                            <h2><small>Bs.</small> {{ $pagos->sum('monto') + $registros_caja->where('tipo', 'ingreso')->sum('monto') }}</h2>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <i class="icon voyager-dollar" style="color: #2E86C1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-bordered" style="border-left: 5px solid #148F77">
                                <div class="panel-body" style="height: 130px">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>Egresos del día</h5>
                                            <h2><small>Bs.</small> {{ $registros_caja->where('tipo', 'egreso')->sum('monto') }}</h2>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <i class="icon voyager-forward" style="color: #148F77"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-bordered" style="border-left: 5px solid #C0392B">
                                <div class="panel-body" style="height: 130px">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>Ventas realizadas</h5>
                                            <h2>{{ count($ventas) }}</h2>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <i class="icon voyager-book" style="color: #C0392B"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-bordered" style="border-left: 5px solid #7D3C98">
                                <div class="panel-body" style="height: 130px">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>Cuotas pendientes</h5>
                                            <h2>{{ count($cuotas) }}</h2>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <i class="icon voyager-calendar" style="color: #7D3C98"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-body" style="height: 290px">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal-delete')
@stop

@section('css')
    <style>
        .panel {
            margin-bottom: 0px !important
        }
        .icon{
            font-size: 50px
        }
    </style>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script>
        $(document).ready(function(){
            const data = {
                labels: [
                    'Pago de cuotas',
                    'Ingresos',
                    'Ventas'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: ["{{ $pagos->where('observaciones', '<>', 'Pago al momento de llevar el equipo.')->sum('monto') }}", "{{ $registros_caja->where('tipo', 'ingreso')->sum('monto') }}", "{{ $pagos->where('observaciones', 'Pago al momento de llevar el equipo.')->sum('monto') }}"],
                    backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            };

            const config = {
                type: 'pie',
                data: data,
            };

            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        });
    </script>
@stop

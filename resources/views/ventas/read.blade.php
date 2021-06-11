@extends('voyager::master')

@section('page_title', 'Ver Servicio')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Servicio
        {{-- <a href="{{ route('servicios.edit', ['servicio' => $id]) }}" class="btn btn-info">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;
            Editar
        </a> --}}
        <a href="{{ route('ventas.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->cliente->nombre_completo }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Garante(s)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @foreach ($reg->garantes as $item)
                                    {{ $item->persona->nombre_completo }} &nbsp;       
                                    @endforeach
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Atendido por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->empleado->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d-M-Y', strtotime($reg->fecha)) }} <small>{{ \Carbon\Carbon::parse($reg->fecha)->diffForHumans() }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->observaciones ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle del servicio</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Detalle</th>
                                                <th style="text-align: right">Precio</th>
                                                <th style="text-align: right">Monto pagado</th>
                                                <th style="text-align: right">Deuda</th>
                                                <th style="text-align: right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reg->detalles as $item)
                                                @php
                                                    $pagos = 0;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @php
                                                            $img = asset('images/phone-default.jpg');
                                                            $imagenes = [];
                                                            if ($item->producto->tipo->imagenes) {
                                                                $imagenes = json_decode($item->producto->tipo->imagenes);
                                                                $img = url('storage/'.str_replace('.', '-cropped.', $imagenes[0]));
                                                            }
                                                            
                                                            foreach ($item->cuotas as $cuota) {
                                                                foreach ($cuota->pagos as $pago) {
                                                                    $pagos += $pago->monto;
                                                                }
                                                            }
                                                        @endphp
                                                        <table>
                                                            <tr>
                                                                <td><img src="{{ $img }}" alt="#" width="50px" /></td>
                                                                <td>
                                                                    <b>{{ $item->producto->tipo->nombre }}</b><br>
                                                                    <small>{{ $item->producto->tipo->marca->nombre }}</small><br>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td style="text-align: right">Bs. {{ $item->precio }}</td>
                                                    <td style="text-align: right">Bs. {{ number_format($pagos, 2, ',', '.') }}</td>
                                                    <td style="text-align: right">Bs. {{ number_format($item->precio - $pagos, 2, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        <button class="btn btn-success btn-sm btn-detalle" data-toggle="modal" data-target="#detalle_modal" data-cuotas='@json($item->cuotas)'>
                                                            <i class="voyager-list"></i> <span class="hidden-xs hidden-sm">Detalles</span>
                                                        </button>
                                                        <button class="btn btn-info btn-sm btn-pago" data-toggle="modal" data-target="#pago_modal" data-cuotas='@json($item->cuotas)'>
                                                            <i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Pago</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle de cuotas --}}
    <div class="modal modal-success fade" tabindex="-1" id="detalle_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-list"></i> Detalles de cuota</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Deuda</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="table-detalle"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle de cuotas --}}
    <form action="{{ route('ventas.pago.store') }}" method="post">
        <div class="modal modal-info fade" tabindex="-1" id="pago_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Agregar pago</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="{{ $reg->id }}">
                        <input type="hidden" name="ventas_detalles_cuota_id" id="input-id_cuota">
                        <input type="hidden" name="monto" id="input-monto">
                        <div class="form-group">
                            <label>Monto a pagar</label>
                            <input type="number" name="pago" id="input-pago" step="0.5" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .btn-sm{
            padding: 2px 8px !important;
            font-weight: 500 !important
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.btn-detalle').click(function(){
                let data = $(this).data('cuotas');
                let detalle = '';
                data.map(item => {
                    let totalPago = 0;
                    item.pagos.map(pago => {
                        totalPago += parseFloat(pago.monto)
                    });
                    detalle += `
                        <tr>
                            <td>${item.tipo}</td>
                            <td>${item.fecha}</td>
                            <td>${item.monto}</td>
                            <td>${item.monto - totalPago.toFixed(2)}</td>
                            <td><span class="text-${item.estado == 'pagada' ? 'success' : 'danger'}">${item.estado}</span></td>
                        </tr>
                    `;
                });
                $('#table-detalle').html(detalle);
            });

            $('.btn-pago').click(function(){
                let data = $(this).data('cuotas');
                let cuota = null;
                data.map(item => {
                    if(item.estado == 'pendiente' && !cuota){
                        cuota = item;
                    }
                });

                if(cuota){
                    let monto = cuota.monto;
                    let montoPagado = 0;
                    cuota.pagos.map(pago => {
                        montoPagado += parseFloat(pago.monto);
                    });
                    $('#input-id_cuota').val(cuota.id);
                    $('#input-monto').val(cuota.monto);
                    $('#input-pago').val(monto-montoPagado);
                    $('#input-pago').attr('max', monto-montoPagado);
                }
            });
        });
    </script>
@stop
@extends('voyager::master')

@section('page_title', 'Ver Cliente/Garante')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Viendo Cliente/Garante
        <a href="{{ route('voyager.personas.index') }}" class="btn btn-warning">
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
                                <h3 class="panel-title">Nombre completo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->nombre_completo }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cédula de identidad</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->ci }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de celular</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><a href="tel: {{ $reg->telefono }}">{{ $reg->telefono }}</a></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->direccion ?? 'No definida' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Lugar de trabajo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->trabajo ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
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
                                <h3 class="panel-title">Historial de ventas</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        {{-- <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Detalle</th>
                                            </tr>
                                        </thead> --}}
                                        <tbody>
                                            @php
                                                $total = 0;
                                                // dd($reg->ventas);
                                            @endphp
                                            @forelse ($reg->ventas as $venta)
                                                <tr>
                                                    <td>{{ $venta->id }}</td>
                                                    <td>
                                                        @foreach ($venta->detalles as $detalle)
                                                            @php
                                                                if ($detalle->producto->estado != 'eliminado') {
                                                                    $total += $detalle->producto->precio_compra;
                                                                }
                                                                $img = asset('images/default.jpg');
                                                                $imagenes = [];
                                                                if ($detalle->producto->tipo->imagenes) {
                                                                    $imagenes = json_decode($detalle->producto->tipo->imagenes);
                                                                    $img = url('storage/'.str_replace('.', '-cropped.', $imagenes[0]));
                                                                }
                                                            @endphp
                                                            <table class="table">
                                                                <tr>
                                                                    <td><img src="{{ $img }}" alt="#" width="50px" /></td>
                                                                    <td>
                                                                        <b>{{ $detalle->producto->tipo->nombre }}</b><br>
                                                                        <small>{{ $detalle->producto->tipo->marca->nombre }}</small><br>
                                                                        <small>IMEI/N&deg; de serie {{ $detalle->producto->imei }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Tipo</th>
                                                                                    <th>Monto</th>
                                                                                    <th>Deuda</th>
                                                                                    <th>Pagos</th>
                                                                                    <th>Estado</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @php
                                                                                    $precio = 0;
                                                                                @endphp
                                                                                @foreach ($detalle->cuotas as $cuota)
                                                                                    @php
                                                                                        $precio += $cuota->monto;
                                                                                        $total_pago = 0;
                                                                                        $pagos = '';
                                                                                        foreach($cuota->pagos as $pago){
                                                                                            $total_pago += $pago->monto;
                                                                                            $pagos .= '<p>'.date('d/m/Y', strtotime($pago->created_at)).' '.$pago->monto.' Bs. <br> <small>'.$pago->observaciones.'</small></p>';
                                                                                        }
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td><b>{{ $cuota->tipo }}</b> <br> {{ date('d/m/Y', strtotime($cuota->fecha)) }}</td>
                                                                                        <td>{{ $cuota->monto }}</td>
                                                                                        <td>{{ $cuota->monto - $total_pago }}</td>
                                                                                        <td>{!! $pagos !!}</td>
                                                                                        <td><label class="label label-{{ $cuota->estado == 'pagada' ? 'success' : 'danger' }}">{{ $cuota->estado }}</label></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td style="text-align: right"><b>Bs. {{ number_format($precio, 2, ',', '.') }}</b></td>
                                                                </tr>
                                                            </table>
                                                        @endforeach
                                                    </td>
                                                    <td class="no-sort no-click bread-actions text-right">
                                                        <a href="{{ route('ventas.show', ['venta' => $venta->id]) }}" title="Ver" class="btn btn-sm btn-warning view" style="margin: 5px 0px; padding: 5px 10px">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2"><h4 class="text-center">No tiene ventas registradas</h4></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ventas en las que ha sido garante</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Detalle</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                // dd($reg->ventas);
                                            @endphp
                                            @forelse ($reg->garante as $garante)
                                                <tr>
                                                    <td>{{ $garante->id }}</td>
                                                    <td>{{ $garante->venta->cliente->nombre_completo }}</td>
                                                    <td>
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Detalle</th>
                                                                    <th style="text-align: right">Precio</th>
                                                                    <th style="text-align: right">Monto pagado</th>
                                                                    <th style="text-align: right">Deuda</th>
                                                                    <th style="text-align: right">Estado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($garante->venta->detalles as $item)
                                                                    @php
                                                                        $pagos = 0;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            @php
                                                                                $img = asset('images/default.jpg');
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
                                                                                        <small>IMEI/N&deg; de serie {{ $item->producto->imei }}</small>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        @php
                                                                            $deuda = $item->precio - $pagos;
                                                                        @endphp
                                                                        <td style="text-align: right">Bs. {{ $item->precio }}</td>
                                                                        <td style="text-align: right">Bs. {{ $pagos }}</td>
                                                                        <td style="text-align: right">Bs. {{ $deuda }}</td>
                                                                        <td style="text-align: right"><label class="label label-{{ $deuda <= 0 ? 'success' : 'danger' }}">{{ $deuda <= 0 ? 'pagada' : 'pendiente' }}</label></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="no-sort no-click bread-actions text-right">
                                                        <a href="{{ route('ventas.show', ['venta' => $item->venta_id]) }}" title="Ver" class="btn btn-sm btn-warning view" style="margin: 5px 0px; padding: 5px 10px">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4"><h4 class="text-center">No ha sido garante de ningún cliente</h4></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop

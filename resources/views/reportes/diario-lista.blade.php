<div class="table-responsive">
    <h4>Ingresos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 50px">N&deg;</th>
                <th>Detalle</th>
                <th class="text-right" style="width: 150px">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_ingresos = 0;
            @endphp
            @forelse ($registros_cajas->where('tipo', 'ingreso') as $registro)
                <tr>
                    <td>{{ $cont }}</td>
                    <td class="@if($registro->deleted_at) deleted @endif">{{ $registro->detalle }}</td>
                    <td class="text-right @if($registro->deleted_at) deleted @endif">{{ $registro->monto }}</td>
                </tr>
                @php
                    $cont++;
                    if(!$registro->deleted_at){
                        $total_ingresos += $registro->monto;
                    }
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No se registraron ingresos</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><h5>TOTAL</h5></td>
                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total_ingresos, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h4>Egresos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 50px">N&deg;</th>
                <th>Detalle</th>
                <th class="text-right" style="width: 150px">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_egreso = 0;
            @endphp
            @forelse ($registros_cajas->where('tipo', 'egreso') as $registro)
                <tr>
                    <td>{{ $cont }}</td>
                    <td class="@if($registro->deleted_at) deleted @endif">{{ $registro->detalle }}</td>
                    <td class="text-right @if($registro->deleted_at) deleted @endif">{{ $registro->monto }}</td>
                </tr>
                @php
                    $cont++;
                    if(!$registro->deleted_at){
                        $total_egreso += $registro->monto;
                    }
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No se registraron egresos</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><h5>TOTAL</h5></td>
                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total_egreso, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h4>Pagos de cuotas</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 50px">N&deg;</th>
                <th>Detalle</th>
                <th class="text-right" style="width: 150px">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_pagos = 0;
            @endphp
            @forelse ($pagos as $pago)
                <tr>
                    <td>{{ $cont }}</td>
                    <td class="@if($pago->deleted_at) deleted @endif">{{ $pago->cuota->detalle->venta->cliente->nombre_completo }} - {{ $pago->cuota->detalle->producto->tipo->marca->nombre }} <b>{{ $pago->cuota->detalle->producto->tipo->nombre }}</b> {{ $pago->cuota->tipo }}</td>
                    <td class="text-right @if($pago->deleted_at) deleted @endif">{{ $pago->monto }}</td>
                </tr>
                @php
                    $cont++;
                    if(!$pago->deleted_at){
                        $total_pagos += $pago->monto;
                    }
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No se registraron pagos de cuotas</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><h5>TOTAL</h5></td>
                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total_pagos, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h4>Pagos de cuotas</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 50px">N&deg;</th>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>
                        @php
                            $img = asset('images/default.jpg');
                            $imagenes = [];
                            if ($venta->producto->tipo->imagenes) {
                                $imagenes = json_decode($venta->producto->tipo->imagenes);
                                $img = asset('storage/'.str_replace(".", "-cropped.", $imagenes[0]));
                            }
                        @endphp
                        <table>
                            <tr>
                                <td><img src="{{ $img }}" class="card-img-top" width="60px" alt="phone"></td>
                                <td>
                                    <b>{{ $venta->producto->tipo->marca->nombre }} {{ $venta->producto->tipo->nombre }}</b><br>
                                    <small>IMEI/N&deg; de serie: {{ $venta->producto->imei }}</small><br>
                                    <small>{{ substr($venta->producto->tipo->detalles, 0, 50) }}...</small>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>{{ $venta->venta->cliente->nombre_completo }}</td>
                    <td>
                        @if ($venta->venta->estado == 'vendido')
                            <div>
                                <small>Precio de venta: </small><b>Bs. {{ number_format($venta->venta->precio, 0, '', '') }}</b><br>
                                <small>Ganancia: </small><b>Bs. {{ $venta->venta->precio-$venta->precio_compra }}</b>
                            </div>
                        @endif
                        @if ($venta->estado == 'crédito')
                            
                        @endif
                        @php
                            $monto_pagado = 0;
                            foreach ($venta->cuotas as $cuota) {
                                foreach ($cuota->pagos as $pago) {
                                    $monto_pagado += $pago->monto;
                                }
                            }
                        @endphp
                        <div>
                            <small>Precio de venta: </small><b>Bs. {{ number_format($venta->precio, 0, '', '') }}</b><br>
                            <small>Ganancia: </small><b>Bs. {{ $venta->precio-$venta->precio_compra }}</b><br>
                            <small>Nro de cuotas: </small><b>{{ count($venta->cuotas->where('tipo', 'cuota')) }}</b>
                            <small> - pagadas: </small><b>{{ count($venta->cuotas->where('estado', 'pagada')->where('tipo', 'cuota')) }}</b> <br>
                            <small>Monto pagado: </small><b>Bs. {{ $monto_pagado }}</b> <br> <small>Deuda: </small><b>Bs. {{ $venta->precio - $monto_pagado }}</b>
                        </div>
                    </td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No se registraron ventas</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .deleted{
        text-decoration: line-through;
        color: #f96868
    }
</style>
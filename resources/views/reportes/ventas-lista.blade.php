<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Cliente</th>
                <th>Producto(s)</th>
                <th>Subtotal</th>
                <th>IVA</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Inversión</th>
                <th>Ganancia</th>
                <th>Pagos realizados</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_inversion = 0;
                $total = 0;
                $pagos_total = 0;
                $total_ganancia = 0;
            @endphp
            @forelse ($ventas as $venta)
                @php
                    $inversion = 0;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $venta->cliente->nombre_completo }}</td>
                    <td>
                        @foreach ($venta->detalles as $detalle)
                            {{ $detalle->producto->tipo->marca->nombre }} <b>{{ $detalle->producto->tipo->nombre }}</b> <br> <small>IMEI/N&deg; de serie {{ $detalle->producto->imei }}</small> <br>
                            @php
                                $inversion += $detalle->producto->precio_compra;
                                $total_inversion += $detalle->producto->precio_compra;
                            @endphp
                        @endforeach
                    </td>
                    <td class="text-right">{{ $venta->detalles->sum('precio') }}</td>
                    <td class="text-right">{{ $venta->iva }}</td>
                    <td class="text-right">{{ $venta->descuento }}</td>
                    <td class="text-right">{{ $venta->detalles->sum('precio') - $venta->descuento + $venta->iva }}</td>
                    <td class="text-right">{{ $inversion }}</td>
                    <td class="text-right">{{ $venta->detalles->sum('precio') - $venta->descuento + $venta->iva - $inversion }}</td>
                    <td class="text-right">
                        @php
                            $pagos = 0;
                            foreach($venta->detalles as $detalle){
                                foreach($detalle->cuotas as $cuota){
                                    $pagos += $cuota->pagos->where('deleted_at', NULL)->sum('monto');
                                }
                            }
                        @endphp
                        {{ $pagos }}
                    </td>
                </tr>
                @php
                    $cont++;
                    $total += $venta->detalles->sum('precio') - $venta->descuento + $venta->iva;
                    $pagos_total += $pagos;
                @endphp
            @empty
                <tr>
                    <td colspan="10"><h4 class="text-center">No se encontraron resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6"></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $total }}</h5></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $total_inversion }}</h5></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $total - $total_inversion }}</h5></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $pagos_total }}</h5></td>
            </tr>
        </tbody>
    </table>
</div>
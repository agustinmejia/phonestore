<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Cliente</th>
                <th>Equipo(s)</th>
                <th>Subtotal</th>
                <th>IVA</th>
                <th>Descuento</th>
                <th>Pagos realizados</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total = 0;
                $pagos_total = 0;
            @endphp
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $venta->cliente->nombre_completo }}</td>
                    <td>
                        @foreach ($venta->detalles as $detalle)
                            {{ $detalle->producto->tipo->marca->nombre }} <b>{{ $detalle->producto->tipo->nombre }}</b> <br> <small>IMEI/N&deg; de serie {{ $detalle->producto->imei }}</small> <br>
                        @endforeach
                    </td>
                    <td>{{ $venta->detalles->sum('precio') }}</td>
                    <td>{{ $venta->iva }}</td>
                    <td>{{ $venta->descuento }}</td>
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
                    <td class="text-right">{{ $venta->detalles->sum('precio') - $venta->descuento + $venta->iva }}</td>
                </tr>
                @php
                    $cont++;
                    $total += $venta->detalles->sum('precio') - $venta->descuento + $venta->iva;
                    $pagos_total += $pagos;
                @endphp
            @empty
                <tr>
                    <td colspan="7"><h4 class="text-center">No se encontraron resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6"></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $pagos_total }}</h5></td>
                <td class="text-right"><h5><small>Bs.</small> {{ $total }}</h5></td>
            </tr>
        </tbody>
    </table>
</div>
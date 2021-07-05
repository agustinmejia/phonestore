<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Monto Pagado</th>
                <th>Deuda</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_pagos = 0;
                $total_deudas = 0;
            @endphp
            @forelse ($deudas as $deuda)
                @php
                    $pagos = 0;
                    foreach($deuda->venta->detalles as $detalle){
                        foreach($detalle->cuotas as $cuota){
                            $pagos += $cuota->pagos->where('deleted_at', NULL)->sum('monto');
                        }
                    }
                    $total_pagos += $pagos;
                @endphp
                @if ($deuda->venta->detalles->sum('precio') - $pagos > 0)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $deuda->venta->cliente->nombre_completo }}</td>
                        <td>
                            @foreach ($deuda->venta->detalles as $detalle)
                                {{ $detalle->producto->tipo->marca->nombre }} <b>{{ $detalle->producto->tipo->nombre }}</b> <br> <small>IMEI/N&deg; de serie {{ $detalle->producto->imei }}</small> <br>
                            @endforeach
                        </td>
                        <td class="text-right">{{ $deuda->venta->detalles->sum('precio') }}</td>
                        <td class="text-right">{{ $pagos }}</td>
                        <td class="text-right">{{ $deuda->venta->detalles->sum('precio') - $pagos }}</td>
                    </tr>
                    @php
                        $cont++;
                        $total_deudas += $deuda->venta->detalles->sum('precio') - $pagos;
                    @endphp
                @endif
            @empty
                <tr>
                    <td colspan="6"><h4 class="text-center">No se encontraron resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4"></td>
                <td class="text-right"><h4><small>Bs.</small> {{ $total_pagos }}</h4></td>
                <td class="text-right"><h4><small>Bs.</small> {{ $total_deudas }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>
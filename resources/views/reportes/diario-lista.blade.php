<div class="table-responsive">
    <h4>Ingresos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Detalle</th>
                <th class="text-right">Monto</th>
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
                    <td>{{ $registro->detalle }}</td>
                    <td class="text-right" style="width: 150px">{{ $registro->monto }}</td>
                </tr>
                @php
                    $cont++;
                    $total_ingresos += $registro->monto;
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No registraron ingresos</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><h5>TOTAL</h5></td>
                <td class="text-right" style="width: 150px"><h4><small>Bs.</small> {{ number_format($total_ingresos, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <h4>Egresos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Detalle</th>
                <th class="text-right">Monto</th>
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
                    <td>{{ $registro->detalle }}</td>
                    <td class="text-right" style="width: 150px">{{ $registro->monto }}</td>
                </tr>
                @php
                    $cont++;
                    $total_egreso += $registro->monto;
                @endphp
            @empty
                <tr>
                    <td colspan="3"><h4 class="text-center">No registraron ingresos</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><h5>TOTAL</h5></td>
                <td class="text-right" style="width: 150px"><h4><small>Bs.</small> {{ number_format($total_egreso, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>
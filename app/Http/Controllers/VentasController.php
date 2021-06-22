<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\TiposProducto;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Venta;
use App\Models\VentasDetalle;
use App\Models\VentasDetallesCuota;
use App\Models\VentasGarante;
use App\Models\VentasDetallesCuotasPago;

class VentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ventas.browse');
    }

    public function list()
    {
        $data = Venta::with(['detalles.producto.tipo.marca', 'cliente', 'garantes.persona', 'detalles.cuotas.pagos'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fecha', function($row){
                return date('d/m/Y', strtotime($row->created_at)).'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('cliente', function($row){
                return '
                    <div class="col-md-12">
                        <b>'.$row->cliente->nombre_completo.'</b><br>
                        <small>Telf: '.('<a href="tel:'.$row->cliente->telefono.'">'.$row->cliente->telefono.'</a>' ?? 'No definido').'</small>
                    </div>';
            })
            ->addColumn('garante', function($row){
                $garantes = '';
                foreach ($row->garantes as $item) {
                    $garantes .= '<li>'.$item->persona->nombre_completo.' <br> <small>Telf: '.('<a href="tel:'.$item->persona->telefono.'">'.$item->persona->telefono.'</a>' ?? 'No definido').'</small></li>';
                }
                return '
                    <div class="col-md-12">
                        <ul>'.$garantes.'</ul>
                    </div>';
            })
            ->addColumn('detalles', function($row){
                $detalles = '';
                foreach ($row->detalles as $item) {
                    $detalles .= '<li>'.$item->producto->tipo->marca->nombre.' <b>'.$item->producto->tipo->nombre.'</b> <br> <small>IMEI '.$item->producto->imei.'</small></li>';
                }
                return '
                    <div class="col-md-12">
                        <ul>'.$detalles.'</ul>
                    </div>';
            })
            ->addColumn('total', function($row){
                $total = 0;
                foreach ($row->detalles as $item) {
                    $total += $item->precio;
                }
                return $total;
            })
            ->addColumn('deuda', function($row){
                $total = 0;
                $pagos = 0;
                $proximo_pago = '';
                foreach ($row->detalles as $item) {
                    $total += $item->precio;
                    foreach ($item->cuotas as $cuota) {
                        if($cuota->estado == 'pendiente' && !$proximo_pago){
                            $proximo_pago = $cuota->fecha;
                        }
                        foreach ($cuota->pagos as $pago) {
                            $pagos += $pago->monto;
                        }
                    }
                }
                return ($total-$pagos).($proximo_pago ? '<br><b style="font-style: bold" class="'.($proximo_pago < date('Y-m-d') ? 'text-danger' : '').($proximo_pago == date('Y-m-d') ? 'text-info' : '').'">Próximo pago '.Carbon::parse($proximo_pago)->diffForHumans().'</b>' : '');
            })
            ->addColumn('action', function($row){
                $total = 0;
                $pagos = 0;
                foreach ($row->detalles as $item) {
                    $total += $item->precio;
                    foreach ($item->cuotas as $cuota) {
                        foreach ($cuota->pagos as $pago) {
                            $pagos += $pago->monto;
                        }
                    }
                }
                $deuda = $total - $pagos;

                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('ventas.show', ['venta' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        <button title="Borrar" class="btn btn-sm btn-danger delete" '.($deuda <= 0 ? 'disabled' : '').' data-toggle="modal" data-target="#delete_modal" onclick="deleteItem('."'".url("admin/ventas/".$row->id)."'".')">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                        </button>
                    </div>
                        ';
                return $actions;
            })
            ->rawColumns(['fecha', 'cliente', 'garante', 'detalles', 'total', 'deuda', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = 'add';
        // $productos = Producto::with(['tipo.marca'])->where('deleted_at', NULL)->where('estado', 'disponible')->orderBy('precio_venta', 'ASC')->get();
        $productos = Marca::with(['tipos.productos.tipo.marca', 'tipos.productos' => function($q){
            $q->where('estado', 'disponible');
        }])->get();
        // dd($productos);
        $personas = Persona::where('deleted_at', NULL)->orderBy('nombre_completo', 'ASC')->get();
        return view('ventas.add-edit', compact('type', 'productos', 'personas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            $fecha = $request->fecha ?? date('Y-m-d');
            $fecha_inicio = $request->fecha_inicio ?? date('Y-m-d');
            $venta = Venta::create([
                'persona_id' => $request->cliente_id,
                'user_id' => Auth::user()->id,
                'observaciones' => $request->observaciones,
                'fecha' => $fecha,
                'descuento' => $request->descuento,
                'iva' => $request->iva
            ]);

            for ($i=0; $i < count($request->garante_id); $i++) {
                $persona = Persona::find($request->garante_id[$i]);
                if(!$persona){
                    $persona = Persona::create(['nombre_completo' => $request->garante_id[$i]]);
                }
                VentasGarante::create([
                    'venta_id' => $venta->id,
                    'persona_id' => $persona->id
                ]);
            }

            for ($i=0; $i < count($request->producto_id); $i++) {
                $detalle = VentasDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $request->producto_id[$i],
                    'precio' => $request->precio[$i]
                ]);

                Producto::where('id', $request->producto_id[$i])->update([
                    'estado' => $request->tipo_venta[$i] == 'credito' ? 'crédito' : 'vendido'
                ]);

                $pago_cuota = in_array($request->producto_id[$i], $request->cuota_inicial_pago ?? []) ? true : false;

                // Pago de la cuota inicial
                $cuota = VentasDetallesCuota::create([
                    'ventas_detalle_id' => $detalle->id,
                    'tipo' => $request->tipo_venta[$i] == 'credito' ? 'cuota inicial' : 'Pago del equipo',
                    'monto' => $request->cuota_inicial[$i] ?? 0,
                    'fecha' => $fecha,
                    'estado' => $pago_cuota ? 'pagada' : 'pendiente'
                ]);

                if ($pago_cuota) {
                    if($request->cuota_inicial[$i]){
                        VentasDetallesCuotasPago::create([
                            'ventas_detalles_cuota_id' => $cuota->id,
                            'user_id' => Auth::user()->id,
                            'monto' => $request->cuota_inicial[$i],
                            'observaciones' => 'Pago al momento de llevar el equipo.'
                        ]);
                    }
                }

                // Calcular periodo de pagos
                if ($request->tipo_venta[$i] == 'credito') {
                    switch ($request->periodo[$i]) {
                        case 'mensual':
                            $periodo = 'month';
                            $cantidad = 1;
                            break;
                        case 'quincenal':
                            $periodo = 'days';
                            $cantidad = 15;
                            break;
                        case 'semanal':
                            $periodo = 'days';
                            $cantidad = 7;
                            break;
                        default:
                            $periodo = 'days';
                            $cantidad = 1;
                            break;
                    }

                    for ($j=0; $j < $request->cuotas[$i]; $j++) {
                        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio."+ $cantidad $periodo"));
                        VentasDetallesCuota::create([
                            'ventas_detalle_id' => $detalle->id,
                            'tipo' => 'cuota',
                            'monto' => $request->pago_cuota[$i],
                            'fecha' => $fecha_inicio,
                            'estado' => 'pendiente'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('ventas.index')->with(['message' => 'Venta guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.index')->with(['message' => 'Ocurrio un error al guardar la venta.', 'alert-type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reg = Venta::with(['detalles.producto.tipo.marca', 'cliente', 'empleado', 'garantes.persona', 'detalles.cuotas.pagos'])
                        ->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($reg);
        return view('ventas.read', compact('reg'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            Venta::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);

            // Eliminar del detalle de venta
            foreach (VentasDetalle::where('venta_id', $id)->get() as $detalle) {
                VentasDetalle::where('id', $detalle->id)->update([
                    'deleted_at' => Carbon::now()
                ]);

                // Restaurar producto
                Producto::where('id', $detalle->producto_id)->update([
                    'estado' => 'disponible'
                ]);

                // Eliminar las cuotas
                foreach (VentasDetallesCuota::where('ventas_detalle_id', $detalle->id)->get() as $cuota) {
                    VentasDetallesCuota::where('id', $cuota->id)->update([
                        'deleted_at' => Carbon::now()
                    ]);

                    // Eliminar los pagos de las cuotas
                    VentasDetallesCuotasPago::where('ventas_detalles_cuota_id', $cuota->id)->update([
                        'deleted_at' => Carbon::now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('ventas.index')->with(['message' => 'Venta anulada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.index')->with(['message' => 'Ocurrio un error al anular la venta.', 'alert-type' => 'error']);
        }
    }

    public function pago_store(Request $request){
        // dd($request);
        DB::beginTransaction();
        try {
            if($request->cuotas){
                foreach ($request->cuotas as $item) {
                    $cuota = VentasDetallesCuota::find($item);
                    $cuota->estado = 'pagada';
                    $cuota->descuento = $request->descuento;
                    $cuota->save();

                    $pagos = VentasDetallesCuotasPago::where('ventas_detalles_cuota_id', $item)->where('deleted_at', NULL)->get();
                    $total_pagos = $pagos->sum('monto');

                    VentasDetallesCuotasPago::create([
                        'ventas_detalles_cuota_id' => $item,
                        'user_id' => Auth::user()->id,
                        'monto' => $cuota->monto - $total_pagos,
                        'observaciones' => $request->observaciones
                    ]);
                }
            }else{
                VentasDetallesCuotasPago::create([
                    'ventas_detalles_cuota_id' => $request->ventas_detalles_cuota_id,
                    'user_id' => Auth::user()->id,
                    'monto' => $request->pago,
                    'observaciones' => $request->observaciones_alt
                ]);

                // Obtener todos los pagos de dicha cuota para cambiar su estado a pagada
                $pagos = VentasDetallesCuotasPago::where('ventas_detalles_cuota_id', $request->ventas_detalles_cuota_id)->where('deleted_at', NULL)->get();
                $total_pagos = $pagos->sum('monto');
                if($total_pagos >= $request->monto){
                    VentasDetallesCuota::where('id', $request->ventas_detalles_cuota_id)->update([
                        'estado' => 'pagada'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('ventas.show', ['venta' => $request->id])->with(['message' => 'Venta guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.show', ['venta' => $request->id])->with(['message' => 'Ocurrio un error al guardar la venta.', 'alert-type' => 'error']);
        }
    }
}

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
use App\Models\Venta;
use App\Models\VentasDetalle;
use App\Models\VentasDetallesCuota;
use App\Models\VentasGarante;
use App\Models\VentasDetallesCuotasPago;

class VentasController extends Controller
{
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
        $data = Venta::with(['detalles.producto.tipo.marca', 'cliente', 'empleado', 'detalles.cuotas.pagos'])
                        ->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('cliente', function($row){
                return '
                    <div class="col-md-12">
                        <b>'.$row->cliente->nombre_completo.'</b><br>
                        <small>Cel: '.($row->cliente->telefono ?? 'No definido').'</small>
                    </div>';
            })
            ->addColumn('empleado', function($row){
                return $row->empleado->name;
            })
            ->addColumn('detalles', function($row){
                $detalles = '';
                foreach ($row->detalles as $item) {
                    $detalles .= '<li>'.$item->producto->tipo->marca->nombre.' <b>'.$item->producto->tipo->nombre.'</b></li>';
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
                return number_format($total, 2, ',', '.');
            })
            ->addColumn('deuda', function($row){
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
                return number_format($total-$pagos, 2, ',', '.');
            })
            ->addColumn('action', function($row){
                $btn_mas = "<li><a href='#' data-toggle='modal' data-target='#etapa_modal' onclick='changeStatus(".(json_encode($row)).")'>Etapa</a></li>";
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('ventas.show', ['venta' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        <a title="Borrar" class="btn btn-sm btn-danger delete" data-toggle="modal" data-target="#delete_modal" onclick="deleteItem('.$row->id.')">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </a>
                    </div>
                        ';
                return $actions;
            })
            ->rawColumns(['cliente', 'empleado', 'detalles', 'total', 'deuda', 'action'])
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
        $productos = Producto::with(['tipo.marca'])->where('deleted_at', NULL)->where('estado', 'disponible')->orderBy('precio_venta', 'ASC')->get();
        $personas = Persona::all()->where('deleted_at', NULL);
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
            $venta = Venta::create([
                'persona_id' => $request->cliente_id,
                'user_id' => Auth::user()->id,
                'observaciones' => $request->observaciones
            ]);

            for ($i=0; $i < count($request->garante_id); $i++) { 
                VentasGarante::create([
                    'venta_id' => $venta->id,
                    'persona_id' => $request->garante_id[$i]
                ]);
            }

            for ($i=0; $i < count($request->producto_id); $i++) { 
                $detalle = VentasDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $request->producto_id[$i],
                    'precio' => $request->precio[$i]
                ]);

                Producto::where('id', $request->producto_id[$i])->update([
                    'estado' => 'vendido'
                ]);

                $fecha = date('Y-m-d');
                $cuota = VentasDetallesCuota::create([
                    'ventas_detalle_id' => $detalle->id,
                    'tipo' => 'cuota inicial',
                    'monto' => $request->cuota_inicial[$i] ?? 0,
                    'fecha' => $fecha,
                    'estado' => 'pagada'
                ]);

                if($request->cuota_inicial[$i]){
                    VentasDetallesCuotasPago::create([
                        'ventas_detalles_cuota_id' => $cuota->id,
                        'user_id' => Auth::user()->id,
                        'monto' => $request->cuota_inicial[$i],
                        'observaciones' => 'Pago al momento de llevar el equipo.'
                    ]);
                }

                // Calcular periodo de pagos
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
                    $fecha = date("Y-m-d", strtotime($fecha."+ $cantidad $periodo"));
                    VentasDetallesCuota::create([
                        'ventas_detalle_id' => $detalle->id,
                        'tipo' => 'cuota',
                        'monto' => $request->pago_cuota[$i],
                        'fecha' => $fecha,
                        'estado' => 'pendiente'
                    ]);
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
        //
    }

    public function pago_store(Request $request){        
        DB::beginTransaction();
        try {
            $pagos = VentasDetallesCuotasPago::where('ventas_detalles_cuota_id', $request->ventas_detalles_cuota_id)->where('deleted_at', NULL)->get();
            $total_pagos = $pagos->sum('monto') + $request->pago;

            VentasDetallesCuotasPago::create([
                'ventas_detalles_cuota_id' => $request->ventas_detalles_cuota_id,
                'user_id' => Auth::user()->id,
                'monto' => $request->pago,
                'observaciones' => $request->observaciones
            ]);

            if($total_pagos >= $request->monto){
                VentasDetallesCuota::where('id', $request->ventas_detalles_cuota_id)->update([
                    'estado' => 'pagada'
                ]);
            }

            DB::commit();
            return redirect()->route('ventas.show', ['venta' => $request->id])->with(['message' => 'Venta guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.show', ['venta' => $request->id])->with(['message' => 'Ocurrio un error al guardar la venta.', 'alert-type' => 'error']);
        }
    }
}

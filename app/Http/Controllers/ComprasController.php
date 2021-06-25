<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\TiposProducto;
use App\Models\Proveedore;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\Categoria;

class ComprasController extends Controller
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
        return view('compras.browse');
    }

    public function list()
    {
        $data = Compra::with(['producto.tipo.marca', 'proveedor', 'empleado'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fecha', function($row){
                return date('d/m/Y', strtotime($row->created_at)).'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('proveedor', function($row){
                return '
                    <div class="col-md-12">
                        <b>'.$row->proveedor->nombre_completo.'</b><br>
                        <small>Cel: '.($row->proveedor->celular ?? 'No definido').'</small>
                    </div>';
            })
            ->addColumn('empleado', function($row){
                return $row->empleado->name;
            })
            ->addColumn('detalles', function($row){
                $detalles = '';
                foreach ($row->producto as $item) {
                    $clase = '';
                    switch ($item->estado) {
                        case 'disponible':
                            $clase = 'success';
                            break;
                        case 'crédito':
                            $clase = 'info';
                            break;
                        case 'vendido':
                            $clase = 'primary';
                            break;
                        case 'eliminado':
                            $clase = 'danger';
                            break;
                    }
                    $detalles .= '<li style="padding: 2px">'.$item->tipo->marca->nombre.' <b>'.$item->tipo->nombre.'</b> <label class="label label-'.$clase.'">'.$item->estado.'</label> <br> <small>IMEI/N&deg; de serie '.$item->imei.'</small> </li>';
                }
                return '
                    <div class="col-md-12">
                        <ul>'.$detalles.'</ul>
                    </div>';
            })
            ->addColumn('total', function($row){
                $total = 0;
                foreach ($row->producto as $item) {
                    if ($item->estado != 'eliminado') {
                        $total += $item->precio_compra;
                    }
                }
                return $total;
            })
            ->addColumn('action', function($row){
                $btn_mas = "<li><a href='#' data-toggle='modal' data-target='#etapa_modal' onclick='changeStatus(".(json_encode($row)).")'>Etapa</a></li>";
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('compras.show', ['compra' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                    </div>
                        ';
                return $actions;
            })
            ->rawColumns(['fecha', 'proveedor', 'empleado', 'detalles', 'action'])
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
        $categorias = Categoria::with(['tipos.marca'])->where('deleted_at', NULL)->get();
        $proveedores = Proveedore::all()->where('deleted_at', NULL);
        return view('compras.add-edit', compact('type', 'categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'user_id' => Auth::user()->id,
                'observaciones' => $request->observaciones
            ]);

            for ($i=0; $i < count($request->productos_tipo_id); $i++) {
                Producto::create([
                    'tipos_producto_id' => $request->productos_tipo_id[$i],
                    'compra_id' => $compra->id,
                    'imei' => $request->imei[$i],
                    'precio_compra' => $request->precio_compra[$i],
                    'precio_venta' => $request->precio_venta[$i],
                    'precio_venta_contado' => $request->precio_venta_contado[$i]
                ]);
            }

            DB::commit();
            return redirect()->route('compras.index')->with(['message' => 'Compra guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('compras.index')->with(['message' => 'Ocurrio un error al guardar la compra.', 'alert-type' => 'error']);
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
        $reg = Compra::with(['producto.tipo.marca', 'proveedor', 'empleado'])->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($reg);
        return view('compras.read', compact('reg'));
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;


// Models
use App\Models\Persona;

class PersonasController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $persona = Persona::create([
                'nombre_completo' => $request->nombre_completo,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'trabajo' => $request->trabajo,
            ]);

            DB::commit();

            if ($request->ajax) {
                return response()->json(['persona' => $persona, 'type' => $request->type]);
            }

            return redirect()->route('personas.index')->with(['message' => 'Persona guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('personas.index')->with(['message' => 'Ocurrio un error al guardar la persona.', 'alert-type' => 'error']);
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
        $reg = Persona::with(['ventas.detalles.producto.tipo', 'ventas.detalles.cuotas.pagos', 'garante.venta.detalles.producto.tipo.marca', 'garante.venta.cliente', 'ventas' => function($q){
            $q->where('deleted_at', NULL)->orderBy('id', 'DESC');
        }])->where('id', $id)->first();
        // dd($reg);
        return view('clientes.read', compact('reg'));
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

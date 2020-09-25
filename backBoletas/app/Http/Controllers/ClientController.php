<?php

namespace App\Http\Controllers;

use Log;
use Validator;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json([
                'code' => 200,
                'message' => 'Consulta de clientes exitosa',
                'status' => true,
                'data' => Client::all()
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Ocurrio un error al consultar los clientes ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar los clientes',
                'status' => false,
                'data' => null
            ], 200);
        }
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
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:1,200',
            'lastname' => 'required|string|between:1,200',
            'type_document' => 'required|string|in:CC,CE,PE',
            'document' => 'required|string|between:1,200|unique:App\Models\Client,document',
            'celphone' => 'required|string|between:1,200',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()->toJson(),
                'status' => false,
                'data' => $request->all()
            ], 200);
        }

        try {
            $client = Client::create($validator->validated());

            return response()->json([
                'code' => 201,
                'message' => 'El cliente se creo correctamente con el codigo '.$client->id,
                'status' => true,
                'data' => $client
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Ocurrio un error al crear cliente: ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un error al crear cliente',
                'status' => false,
                'data' => $request->all()
            ], 200);
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
        try {
            $client = Client::with('reservations')->where('document', $id)->first();

            return response()->json([
                'code' => 201,
                'message' => 'Consulta exitosa',
                'status' => true,
                'data' => $client
            ], 200);
            
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error consultar el cliente  ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar el cliente',
                'status' => false,
                'data' => null
            ], 200);
        }
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
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:1,200',
            'lastname' => 'required|string|between:1,200',
            'celphone' => 'required|string|between:1,200',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()->toJson(),
                'status' => false,
                'data' => $request->all()
            ], 200);
        }

        try {
            $client = Client::find($id);

            $client->firstname = $validator->validated()['firstname'];
            $client->lastname = $validator->validated()['lastname'];
            $client->celphone = $validator->validated()['celphone'];

            $client->save();
            
            return response()->json([
                'code' => 201,
                'message' => 'Cliente editado correctamente',
                'status' => true,
                'data' => $client
            ], 201);
            
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error al actualizar el cliente ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro actualizar el evento ',
                'status' => false,
                'data' => null
            ], 200);
        }
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

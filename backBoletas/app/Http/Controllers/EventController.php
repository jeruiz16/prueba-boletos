<?php

namespace App\Http\Controllers;

use Log;
use Validator;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Traits\ReservationsTrait;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
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
                'message' => 'Consulta de eventos exitosa',
                'status' => true,
                'data' => Event::all()
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Ocurrio un error al consultar los eventos ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar los eventos',
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
            'name' => 'required|string|between:1,200',
            'date' => 'required|date|date_format:Y-m-d H:i:s|after:today',
            'capacity' => 'required|digits_between:0,99999999',
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
            DB::beginTransaction();

            $event = Event::create($validator->validated());
            $capacity = $validator->validated()['capacity'];

            for ($i=0; $i < $capacity; $i++) { 
                $ticket = new Ticket();
                $ticket->event_id = $event->event_id;
                $ticket->save();
            }

            DB::commit();

            return response()->json([
                'code' => 201,
                'message' => 'El evento se creo correctamente con el codigo '.$event->id,
                'status' => true,
                'data' => $event
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Ocurrio un error al crear evento: ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un error al crear evento',
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
            $event = Event::with('tickets')->find($id);
            $capacity = ReservationsTrait::capacityEvent($id);
            $event->used = $capacity;

            return response()->json([
                'code' => 201,
                'message' => 'Consulta exitosa',
                'status' => true,
                'data' => $event
            ], 200);
            
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error consultar el evento  ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar el evento',
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
            'name' => 'required|string|between:1,200',
            'date' => 'required|date|date_format:Y-m-d H:i:s|after:today'
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
            $event = Event::find($id);

            $event->name = $validator->validated()['name'];
            $event->date = $validator->validated()['date'];

            $event->save();
            
            return response()->json([
                'code' => 201,
                'message' => 'Evento editado correctamente',
                'status' => true,
                'data' => $event
            ], 201);
            
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error al actualizar el evento ' . $e->getMessage());
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

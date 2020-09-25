<?php

namespace App\Http\Controllers;

use Log;
use Validator;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Traits\ReservationsTrait;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
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
                'message' => 'Consulta de reservas exitosa',
                'status' => true,
                'data' => Reservation::all()
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error al consultar las reservas ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar las reservas',
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
            'client_id' => 'required|numeric|exists:App\Models\Client,client_id',
            'event_id' => 'required|numeric|exists:App\Models\Event,event_id',
            'quantity' => 'required|numeric',
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

            $clientId = $validator->validated()['client_id'];
            $eventId = $validator->validated()['event_id'];
            $quantity = $validator->validated()['quantity'];
            $capacityUsed = ReservationsTrait::capacityEvent($eventId);
            $event = Event::find($eventId);
            $available = $event->capacity - $capacityUsed;

            if( $available < $quantity){
                return response()->json([
                    'code' => 400,
                    'message' => 'La cantidad de boletas solicitadas supera la cantidad disponible',
                    'status' => false,
                    'data' => array_merge($request->all(), ["available" => $available])
                ], 200);
            }

            $tickets = $this->ticketsAvalible($eventId, $quantity);
            $reservationsClient = [];

            DB::beginTransaction();
            foreach ($tickets as $ticket => $ticketData) {
                $reservationTicket =  new Reservation();
                $reservationTicket->ticket_id = $ticketData->ticket_id;
                $reservationTicket->client_id = $clientId;
                $reservationTicket->save();
                $reservationsClient[] = $reservationTicket;
            }
            DB::commit(); 

            return response()->json([
                'code' => 201,
                'message' => 'Se realizo la reserva de las boletas correctamente',
                'status' => true,
                'data' => $reservationsClient
            ], 201);
            
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Ocurrio un error al realizar la reserva ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un error al realizar la reserva ',
                'status' => false,
                'data' => null
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
        //
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

    private function ticketsAvalible($idEvent, $limit){
        try {
            $tickets = Event::select('tickets.ticket_id')
                ->join('tickets', 'tickets.event_id' ,  '=', 'events.event_id')
                ->leftjoin('reservations', 'reservations.ticket_id', '=', 'tickets.ticket_id')
                ->where('events.event_id', $idEvent)
                ->whereNull('reservations.reservation_id')
                ->take($limit)
                ->get();
            
            return $tickets;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function listReservationsClientDocument($document){
        try {
            $reservations = Reservation::join('clients', 'clients.client_id' ,  '=', 'reservations.client_id')
                ->join('tickets', 'tickets.ticket_id', '=', 'reservations.ticket_id')
                ->join('events', 'events.event_id', '=', 'tickets.event_id')
                ->where('clients.document', $document)
                ->get();
            
            return response()->json([
                'code' => 200,
                'message' => 'Consulta de reservas exitosa',
                'status' => true,
                'data' => $reservations
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Ocurrio un error al consultar las reservas ' . $e->getMessage());
            return response()->json([
                'code' => 400,
                'message' => 'Ocurrio un erro al consultar las reservas',
                'status' => false,
                'data' => null
            ], 200);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Services\PropertyService;
use App\Services\GuestService;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;
    protected $propertyService;
    protected $guestService;

    public function __construct(
        ReservationService $reservationService,
        PropertyService $propertyService,
        GuestService $guestService
    ) {
        $this->reservationService = $reservationService;
        $this->propertyService = $propertyService;
        $this->guestService = $guestService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'check_in', 'check_out']);
        $reservations = $this->reservationService->getAllReservations($filters);
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $properties = $this->propertyService->getAvailableProperties();
        $guests = $this->guestService->getAllGuests();
        return view('reservations.create', compact('properties', 'guests'));
    }

    public function store(ReservationRequest $request)
    {
        $reservation = $this->reservationService->createReservation($request->validated());
        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reserva criada com sucesso.');
    }

    public function show($id)
    {
        $reservation = $this->reservationService->getReservation($id);
        return view('reservations.show', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = $this->reservationService->getReservation($id);
        $properties = $this->propertyService->getAllProperties();
        $guests = $this->guestService->getAllGuests();
        return view('reservations.edit', compact('reservation', 'properties', 'guests'));
    }

    public function update(ReservationRequest $request, $id)
    {
        $this->reservationService->updateReservation($id, $request->validated());
        return redirect()->route('reservations.index')
            ->with('success', 'Reserva atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $this->reservationService->deleteReservation($id);
        return redirect()->route('reservations.index')
            ->with('success', 'Reserva removida com sucesso.');
    }

    public function updateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:confirmed,cancelled,completed'
        ]);

        $this->reservationService->updateReservationStatus($id, $request->status);
        return redirect()->route('reservations.index')
            ->with('success', 'Status da reserva atualizado com sucesso.');
    }
}

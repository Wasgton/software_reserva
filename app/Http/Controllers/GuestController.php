<?php

namespace App\Http\Controllers;

use App\Services\GuestService;
use App\Http\Requests\GuestRequest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    protected $guestService;

    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    public function index(Request $request)
    {
        $guests = $this->guestService->getAllGuests();
        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(GuestRequest $request)
    {
        $guest = $this->guestService->createGuest($request->validated());
        return redirect()->route('guests.index')
            ->with('success', 'Hóspede cadastrado com sucesso.');
    }

    public function show($id)
    {
        $guest = $this->guestService->getGuest($id);
        $stayHistory = $this->guestService->getGuestStayHistory($id);
        return view('guests.show', compact('guest', 'stayHistory'));
    }

    public function edit($id)
    {
        $guest = $this->guestService->getGuest($id);
        return view('guests.edit', compact('guest'));
    }

    public function update(GuestRequest $request, $id)
    {
        $this->guestService->updateGuest($id, $request->validated());
        return redirect()->route('guests.index')
            ->with('success', 'Hóspede atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $this->guestService->deleteGuest($id);
        return redirect()->route('guests.index')
            ->with('success', 'Hóspede removido com sucesso.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\OwnerService;
use App\Http\Requests\OwnerRequest;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    protected $ownerService;

    public function __construct(OwnerService $ownerService)
    {
        $this->ownerService = $ownerService;
    }

    public function index(Request $request)
    {
        $owners = $this->ownerService->getAllOwners();
        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('owners.create');
    }

    public function store(OwnerRequest $request)
    {
        $owner = $this->ownerService->createOwner($request->validated());
        return redirect()->route('owners.index')
            ->with('success', 'Proprietário cadastrado com sucesso.');
    }

    public function show($id)
    {
        $owner = $this->ownerService->getOwner($id);
        return view('owners.show', compact('owner'));
    }

    public function edit($id)
    {
        $owner = $this->ownerService->getOwner($id);
        return view('owners.edit', compact('owner'));
    }

    public function update(OwnerRequest $request, $id)
    {
        $this->ownerService->updateOwner($id, $request->validated());
        return redirect()->route('owners.index')
            ->with('success', 'Proprietário atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $this->ownerService->deleteOwner($id);
        return redirect()->route('owners.index')
            ->with('success', 'Proprietário removido com sucesso.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\PropertyService;
use App\Services\OwnerService;
use App\Http\Requests\PropertyRequest;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyService;
    protected $ownerService;

    public function __construct(
        PropertyService $propertyService,
        OwnerService $ownerService
    ) {
        $this->propertyService = $propertyService;
        $this->ownerService = $ownerService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['city', 'state', 'status']);
        $properties = $this->propertyService->getAllProperties($filters);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $owners = $this->ownerService->getAllOwners();
        return view('properties.create', compact('owners'));
    }

    public function store(PropertyRequest $request)
    {
        $property = $this->propertyService->createProperty($request->validated());
        return redirect()->route('properties.index')
            ->with('success', 'Propriedade criada com sucesso.');
    }

    public function show($id)
    {
        $property = $this->propertyService->getProperty($id);
        return view('properties.show', compact('property'));
    }

    public function edit($id)
    {
        $property = $this->propertyService->getProperty($id);
        $owners = $this->ownerService->getAllOwners();
        return view('properties.edit', compact('property', 'owners'));
    }

    public function update(PropertyRequest $request, $id)
    {
        $this->propertyService->updateProperty($id, $request->validated());
        return redirect()->route('properties.index')
            ->with('success', 'Propriedade atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $this->propertyService->deleteProperty($id);
        return redirect()->route('properties.index')
            ->with('success', 'Propriedade removida com sucesso.');
    }
}

@extends('layouts.app')

@section('title', 'Nova Propriedade')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Nova Propriedade</h1>
            <a href="{{ route('properties.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('properties.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Imóvel</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código da Acomodação</label>
                        <input type="text" name="accommodation_code" value="{{ old('accommodation_code') }}"
                            class="w-full rounded-md border-gray-300 @error('accommodation_code') border-red-500 @enderror">
                        @error('accommodation_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proprietário</label>
                        <select name="owner_id" class="w-full rounded-md border-gray-300 @error('owner_id') border-red-500 @enderror">
                            <option value="">Selecione um proprietário</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('owner_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full rounded-md border-gray-300 @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood') }}"
                            class="w-full rounded-md border-gray-300 @error('neighborhood') border-red-500 @enderror">
                        @error('neighborhood')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full rounded-md border-gray-300 @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" name="state" value="{{ old('state') }}"
                            class="w-full rounded-md border-gray-300 @error('state') border-red-500 @enderror">
                        @error('state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valor da Diária</label>
                        <input type="number" step="0.01" name="daily_rate" value="{{ old('daily_rate') }}"
                            class="w-full rounded-md border-gray-300 @error('daily_rate') border-red-500 @enderror">
                        @error('daily_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Percentual de Comissão (%)</label>
                        <input type="number" step="0.01" name="commission_percentage" value="{{ old('commission_percentage') }}"
                            class="w-full rounded-md border-gray-300 @error('commission_percentage') border-red-500 @enderror">
                        @error('commission_percentage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Cadastrar Propriedade
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

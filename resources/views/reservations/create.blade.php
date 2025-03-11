@extends('layouts.app')

@section('title', 'Nova Reserva')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Nova Reserva</h1>
            <a href="{{ route('reservations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hóspede</label>
                        <select name="guest_id" class="w-full rounded-md border-gray-300 @error('guest_id') border-red-500 @enderror">
                            <option value="">Selecione um hóspede</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                    {{ $guest->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('guest_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Propriedade</label>
                        <select name="property_id" class="w-full rounded-md border-gray-300 @error('property_id') border-red-500 @enderror">
                            <option value="">Selecione uma propriedade</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }} - R$ {{ number_format($property->daily_rate, 2, ',', '.') }}/dia
                                </option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                        <input type="date" name="check_in" value="{{ old('check_in') }}"
                            class="w-full rounded-md border-gray-300 @error('check_in') border-red-500 @enderror">
                        @error('check_in')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                        <input type="date" name="check_out" value="{{ old('check_out') }}"
                            class="w-full rounded-md border-gray-300 @error('check_out') border-red-500 @enderror">
                        @error('check_out')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Taxa de Limpeza</label>
                        <input type="number" step="0.01" name="cleaning_fee" value="{{ old('cleaning_fee') }}"
                            class="w-full rounded-md border-gray-300 @error('cleaning_fee') border-red-500 @enderror">
                        @error('cleaning_fee')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Criar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

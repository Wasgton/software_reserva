@extends('layouts.app')

@section('title', 'Novo Proprietário')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Novo Proprietário</h1>
            <a href="{{ route('owners.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('owners.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profissão</label>
                        <input type="text" name="profession" value="{{ old('profession') }}"
                            class="w-full rounded-md border-gray-300 @error('profession') border-red-500 @enderror">
                        @error('profession')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Pessoa</label>
                        <select name="person_type" class="w-full rounded-md border-gray-300 @error('person_type') border-red-500 @enderror">
                            <option value="PF" {{ old('person_type') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="PJ" {{ old('person_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                        @error('person_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nacionalidade</label>
                        <input type="text" name="nationality" value="{{ old('nationality') }}"
                            class="w-full rounded-md border-gray-300 @error('nationality') border-red-500 @enderror">
                        @error('nationality')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CPF/CNPJ</label>
                        <input type="text" name="document_number" value="{{ old('document_number') }}"
                            class="w-full rounded-md border-gray-300 @error('document_number') border-red-500 @enderror">
                        @error('document_number')
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full rounded-md border-gray-300 @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" name="state" value="{{ old('state') }}" maxlength="2"
                            class="w-full rounded-md border-gray-300 @error('state') border-red-500 @enderror">
                        @error('state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                            class="w-full rounded-md border-gray-300 @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Cadastrar Proprietário
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

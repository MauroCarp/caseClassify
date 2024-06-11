<div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RFID</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">G.D</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AoB</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo AoB</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carcasa</th>
                <th class="px-6 py-3 bg-gray-50"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <!-- Fila de inputs para agregar un nuevo registro -->
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="category" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Categoria">
                    @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="rfid" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="RFID">
                    @error('rfid') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="weight" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Peso">
                    @error('weight') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="gd" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="G.D">
                    @error('gd') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="AoB" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="AoB">
                    @error('AoB') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" wire:model="AoBType" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Tipo AoB">
                    @error('AoBType') <span class="text-red-500">{{ $message }}</span> @enderror
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button wire:click="addAnimal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cargar</button>
                </td>
            </tr>
            <!-- Registros existentes -->
            @foreach($animals as $animal)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->category }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->rfid }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->weight }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->gd }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->AoB }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->AoBType }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $animal->case }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

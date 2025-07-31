<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Medicamento de {{ $user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para editar medicamento del paciente</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <div class="mt-6">
            <a href="{{ route('users.medicines', $user) }}"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Medicamentos
            </a>
        </div>

        <div>
            <form class="mt-6 space-y-6" action="{{ route('users.medicines.update', [$user, $patientMedicine]) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <flux:select label="Medicamento" name="medicine_id" required>
                        @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}" {{ old('medicine_id', $patientMedicine->medicine_id) == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input label="Dosis" type="text" name="dosage" placeholder="Ej: 1 tableta cada 8 horas" 
                        value="{{ old('dosage', $patientMedicine->dosage) }}" required />

                    <flux:input label="Cantidad" type="number" name="quantity" min="1" 
                        value="{{ old('quantity', $patientMedicine->quantity) }}" required />

                    <flux:input label="Fecha de Inicio" type="date" name="start_date" 
                        value="{{ old('start_date', $patientMedicine->start_date->format('Y-m-d')) }}" required />

                    <flux:input label="Fecha de Fin" type="date" name="end_date" 
                        value="{{ old('end_date', $patientMedicine->end_date?->format('Y-m-d')) }}" />

                    <flux:select label="Estado" name="status" required class="lg:col-span-2">
                        <option value="active" {{ old('status', $patientMedicine->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="suspended" {{ old('status', $patientMedicine->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                        <option value="completed" {{ old('status', $patientMedicine->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                    </flux:select>
                </div>
                <flux:button type="submit" variant="primary">Actualizar Medicamento</flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>
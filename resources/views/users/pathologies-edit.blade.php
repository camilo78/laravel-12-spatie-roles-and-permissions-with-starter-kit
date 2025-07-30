<x-layouts.app>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Patología de {{ $user->name }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Formulario para editar patología del paciente</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div>
        <div class="mt-6">
            <a href="{{ route('users.pathologies', $user) }}"
                class="cursor-pointer px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar a Patologías
            </a>
        </div>

        <div>
            <form class="mt-6 space-y-6" action="{{ route('users.pathologies.update', [$user, $patientPathology]) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <flux:select label="Patología" name="pathology_id" required>
                        @foreach($pathologies as $pathology)
                        <option value="{{ $pathology->id }}" {{ old('pathology_id', $patientPathology->pathology_id) == $pathology->id ? 'selected' : '' }}>{{ $pathology->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input label="Fecha de Diagnóstico" type="date" name="diagnosed_at" 
                        value="{{ old('diagnosed_at', $patientPathology->diagnosed_at->format('Y-m-d')) }}" required />

                    <flux:select label="Estado" name="status" required>
                        <option value="active" {{ old('status', $patientPathology->status) == 'active' ? 'selected' : '' }}>Activa</option>
                        <option value="inactive" {{ old('status', $patientPathology->status) == 'inactive' ? 'selected' : '' }}>Inactiva</option>
                        <option value="controlled" {{ old('status', $patientPathology->status) == 'controlled' ? 'selected' : '' }}>Controlada</option>
                    </flux:select>
                </div>
                <flux:button type="submit" variant="primary">Actualizar Patología</flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>
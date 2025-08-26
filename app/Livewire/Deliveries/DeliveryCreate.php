<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use App\Models\User;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use App\Models\PatientMedicine;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

/**
 * Componente Livewire para crear nuevas entregas de medicamentos
 * 
 * Este componente maneja la creación de entregas de medicamentos
 * y la asignación automática de pacientes con medicamentos activos
 * 
 * @package App\Livewire\Deliveries
 */
class DeliveryCreate extends Component
{
    /**
     * Nombre de la entrega
     * 
     * @var string
     */
    public $name = '';

    /**
     * Fecha de inicio de la entrega
     * 
     * @var string
     */
    public $start_date = '';

    /**
     * Fecha de fin de la entrega
     * 
     * @var string
     */
    public $end_date = '';

    /**
     * Estado de envío del formulario para prevenir doble envío
     * 
     * @var bool
     */
    public $isSubmitting = false;
    
    /**
     * Valida que la fecha de inicio esté dentro del rango permitido:
     * - Mes actual completo
     * - 10 días antes del mes actual
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateStartDate()
    {
        $startDate = \Carbon\Carbon::parse($this->start_date);
        $now = \Carbon\Carbon::now();
        
        // Calcular el rango permitido
        $minDate = $now->copy()->startOfMonth()->subDays(10);
        $maxDate = $now->copy()->endOfMonth();
        
        if ($startDate->lt($minDate) || $startDate->gt($maxDate)) {
            $this->addError('start_date', 'La fecha de inicio debe estar entre ' . 
                $minDate->format('d/m/Y') . ' y ' . $maxDate->format('d/m/Y') . '.');
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Support\Facades\Validator::make([], [])
            );
        }
    }

    /**
     * Reglas de validación del formulario
     * 
     * @var array
     */
    protected $rules = [
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];
    
    /**
     * Guarda la nueva entrega y asigna pacientes con medicamentos activos
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        // Prevenir doble envío
        if ($this->isSubmitting) return;
        
        $this->isSubmitting = true;
        
        try {
            // Validar los datos del formulario
            $this->validate();
            
            // Validación personalizada para start_date
            $this->validateStartDate();

            // Usar transacción para asegurar consistencia de datos
            DB::transaction(function () {
                // Crear la entrega de medicamentos
                $delivery = MedicineDelivery::create([
                    'name' => $this->name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                ]);

                // Obtener usuarios activos con patologías activas
                $activeUsers = User::where('status', true)
                    ->whereHas('patientPathologies', fn($q) => $q->where('status', 'active'))
                    ->get();
                
                // Procesar cada usuario activo
                foreach ($activeUsers as $user) {
                    // Obtener medicamentos activos del usuario
                    $activeMedicines = PatientMedicine::where('user_id', $user->id)
                        ->where('status', 'active')
                        ->get();

                    // Si el usuario tiene medicamentos activos, agregarlo a la entrega
                    if ($activeMedicines->isNotEmpty()) {
                        // Crear registro de paciente en la entrega
                        $deliveryPatient = DeliveryPatient::create([
                            'medicine_delivery_id' => $delivery->id,
                            'user_id' => $user->id,
                        ]);

                        // Agregar cada medicamento activo a la entrega
                        foreach ($activeMedicines as $medicine) {
                            DeliveryMedicine::create([
                                'delivery_patient_id' => $deliveryPatient->id,
                                'patient_medicine_id' => $medicine->id,
                            ]);
                        }
                    }
                }
            });

            // Mostrar mensaje de éxito y redireccionar
            session()->flash('success', 'Entrega creada exitosamente.');
            return redirect()->route('deliveries.index');
        } catch (\Exception $e) {
            // Resetear estado de envío en caso de error
            $this->isSubmitting = false;
            throw $e;
        }
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.deliveries.delivery-create');
    }
}
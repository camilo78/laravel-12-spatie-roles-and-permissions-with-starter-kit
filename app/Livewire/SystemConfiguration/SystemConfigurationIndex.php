<?php

namespace App\Livewire\SystemConfiguration;

use App\Models\SystemConfiguration;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SystemConfigurationIndex extends Component
{
    use WithFileUploads;

    public $hospital_name;
    public $program_name;
    public $program_manager;
    public $first_delivery_days;
    public $subsequent_delivery_days;
    public $app_logo;
    public $hospital_logo;
    public $current_app_logo;
    public $current_hospital_logo;

    protected $rules = [
        'hospital_name' => 'required|string|max:255',
        'program_name' => 'required|string|max:255',
        'program_manager' => 'required|string|max:255',
        'first_delivery_days' => 'required|integer|min:1|max:365',
        'subsequent_delivery_days' => 'required|integer|min:1|max:365',
        'app_logo' => 'nullable|image|max:2048',
        'hospital_logo' => 'nullable|image|max:2048'
    ];

    public function mount()
    {
        $config = SystemConfiguration::getConfig();
        $this->hospital_name = $config->hospital_name;
        $this->program_name = $config->program_name;
        $this->program_manager = $config->program_manager;
        $this->first_delivery_days = $config->first_delivery_days;
        $this->subsequent_delivery_days = $config->subsequent_delivery_days;
        $this->current_app_logo = $config->app_logo;
        $this->current_hospital_logo = $config->hospital_logo;
    }

    public function save()
    {
        $this->validate();

        $config = SystemConfiguration::getConfig();
        
        $data = [
            'hospital_name' => $this->hospital_name,
            'program_name' => $this->program_name,
            'program_manager' => $this->program_manager,
            'first_delivery_days' => $this->first_delivery_days,
            'subsequent_delivery_days' => $this->subsequent_delivery_days
        ];

        if ($this->app_logo) {
            if ($config->app_logo) {
                Storage::disk('public')->delete($config->app_logo);
            }
            $data['app_logo'] = $this->app_logo->store('logos', 'public');
        }

        if ($this->hospital_logo) {
            if ($config->hospital_logo) {
                Storage::disk('public')->delete($config->hospital_logo);
            }
            $data['hospital_logo'] = $this->hospital_logo->store('logos', 'public');
        }

        $config->update($data);
        SystemConfiguration::clearCache();

        $this->current_app_logo = $config->fresh()->app_logo;
        $this->current_hospital_logo = $config->fresh()->hospital_logo;
        $this->app_logo = null;
        $this->hospital_logo = null;

        session()->flash('success', 'Configuraciones actualizadas exitosamente.');
    }

    public function render()
    {
        return view('livewire.system-configuration.system-configuration-index');
    }
}
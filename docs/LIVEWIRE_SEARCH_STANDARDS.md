# Estándares para Componentes Livewire con Búsqueda

## Estructura Estandarizada

### 1. Trait HasSearchableQueries
Proporciona funcionalidad común para todos los componentes con búsqueda:

```php
use App\Traits\HasSearchableQueries;

class MiComponente extends Component
{
    use HasSearchableQueries;
    
    // Automáticamente incluye:
    // - $search, $perPage, $sortField, $sortDirection
    // - updatingSearch(), updatingPerPage(), sortBy()
    // - Métodos de sanitización y aplicación segura de filtros
}
```

### 2. BaseIndexComponent (Abstracto)
Para componentes de índice estandarizados:

```php
use App\Livewire\BaseIndexComponent;

class MiIndex extends BaseIndexComponent
{
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like',           // Búsqueda con LIKE
            'email' => 'exact',         // Búsqueda exacta
            'user.name' => 'relation'   // Búsqueda en relación
        ];
    }
    
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'created_at'];
    }
    
    protected function getModelClass(): string
    {
        return MiModelo::class;
    }
}
```

## Patrones de Implementación

### Componentes Index Simples
```php
class UserIndex extends BaseIndexComponent
{
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like',
            'dni' => 'like',
            'email' => 'like'
        ];
    }
    
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'dni', 'created_at'];
    }
    
    protected function getModelClass(): string
    {
        return User::class;
    }
    
    public function render()
    {
        $users = $this->buildQuery();
        return view('livewire.users.user-index', compact('users'));
    }
}
```

### Componentes con Filtros Adicionales
```php
class LocalityIndex extends BaseIndexComponent
{
    public $selectedDepartment = '';
    public $selectedMunicipality = '';
    
    protected function applyAdditionalFilters($query)
    {
        if (empty($this->selectedMunicipality)) {
            return $query->whereRaw('1 = 0'); // No mostrar resultados
        }
        
        return $query->where('municipality_id', $this->selectedMunicipality);
    }
    
    public function updatedSelectedDepartment($value)
    {
        $this->selectedMunicipality = '';
        $this->resetPage();
    }
}
```

## Validaciones y Seguridad

### 1. Sanitización Automática
```php
// El trait sanitiza automáticamente:
protected function sanitizeSearch($search)
{
    if (empty($search)) return '';
    
    // Elimina caracteres peligrosos
    $sanitized = trim(preg_replace('/[^\p{L}\p{N}\s\-_.@]/u', '', $search));
    
    // Limita longitud
    return substr($sanitized, 0, 100);
}
```

### 2. Validación de Campos de Ordenamiento
```php
protected function applySorting(Builder $query, array $sortableFields = [])
{
    // Valida que el campo esté permitido
    if (!empty($sortableFields) && !in_array($this->sortField, $sortableFields)) {
        $this->sortField = $sortableFields[0] ?? 'id';
    }
    
    // Valida dirección
    if (!in_array($this->sortDirection, ['asc', 'desc'])) {
        $this->sortDirection = 'desc';
    }
}
```

### 3. Manejo de Errores
```php
try {
    return $query->paginate($this->getValidatedPerPage());
} catch (\Exception $e) {
    Log::error('Error en paginación: ' . $e->getMessage());
    
    // Fallback seguro
    return $query->getModel()->newQuery()->paginate(10);
}
```

## Optimizaciones

### 1. Eager Loading Consistente
```php
protected function getEagerLoadRelations(): array
{
    return ['roles', 'department', 'municipality'];
}
```

### 2. Consultas Eficientes
```php
// ✅ Correcto: Búsqueda en relaciones
'user.name' => 'relation'  // Genera whereHas optimizado

// ❌ Incorrecto: Join manual
$query->join('users', 'users.id', '=', 'table.user_id')
      ->where('users.name', 'like', "%{$search}%")
```

## Errores Comunes Evitados

### 1. Inyección SQL
```php
// ❌ Peligroso
$query->whereRaw("name LIKE '%{$search}%'");

// ✅ Seguro
$query->where('name', 'LIKE', "%{$search}%");
```

### 2. Consultas N+1
```php
// ❌ Genera N+1
$users = User::all();
foreach($users as $user) {
    echo $user->department->name; // Query por cada usuario
}

// ✅ Optimizado
$users = User::with('department')->get();
```

## Checklist de Implementación

- [ ] Usar `HasSearchableQueries` trait o `BaseIndexComponent`
- [ ] Definir `getSearchableFields()` con tipos correctos
- [ ] Definir `getSortableFields()` para campos permitidos
- [ ] Implementar `getEagerLoadRelations()` para optimizar consultas
- [ ] Agregar manejo de errores con try/catch y logging
- [ ] Validar permisos en métodos de eliminación
- [ ] Usar `wire:confirm` para confirmaciones de eliminación
- [ ] Implementar fallbacks seguros para consultas fallidas
- [ ] Documentar métodos con comentarios en español
- [ ] Probar con datos de prueba y casos extremos
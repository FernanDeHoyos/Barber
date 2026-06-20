<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end mt-4">
            <x-filament::button type="submit">
                Guardar Cambios
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

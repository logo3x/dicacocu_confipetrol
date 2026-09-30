<x-filament-panels::page>
    <form wire:submit="guardar">
        {{ $this->form }}

        <div class="mt-6 flex gap-3">
            <x-filament::button type="submit">
                Guardar y continuar
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

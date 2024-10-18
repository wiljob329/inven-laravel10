<div class="overflow-hidden overflow-x-auto rounded-lg border border-gray-300">
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Material</th>
                <th class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Cantidad</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->getMaterialesTabla() as $index => $material)
                <tr>
                    <td class="py-4 px-6 text-sm text-gray-900 whitespace-nowrap">
                        {{ \App\Models\Material::find($material['material_id'])->descripcion ?? 'N/A' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-900 whitespace-nowrap">
                        {{ $material['cantidad'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- @push('scripts')
    -->
    <!--     <script>
        -- >
        <
        !--document.addEventListener('livewire:load', function() {
            -- >
            <
            !--Livewire.on('repeater::valueUpdated', function(data) {
                -- >
                <
                !--
                if (data.name === 'articulo_temporal') {
                    -- >
                    <
                    !--Livewire.emit('repeaterUpdated', data.state);
                    -- >
                    <
                    !--
                }-- >
                <
                !--
            });
            -- >
            <
            !--
        });
        -- >
        <
        !--
    </script> -->
    <!--
@endpush -->

<div>
    <div id="sankey" wire:ignore></div>
</div>

@push('after-scripts')

{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/apexsankey"></script> --}}

<script>
document.addEventListener('livewire:load', function () {

    let graph = null;

    const options = {
        width: '100%',
        height: 520,
        nodeWidth: 18,
        fontFamily: 'Inter, sans-serif',
        fontWeight: '500',
        fontSize: '12px',
        fontColor: '#334155',
        edgeGradientFill: true,
        edgeOpacity: 0.35,
        enableToolbar: true,
        canvasStyle: `
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fafafa;
            box-sizing: border-box;
        `,
    };

    function renderSankey(data) {

        console.log('Sankey data:', data);

        const container = document.getElementById('sankey');

        if (!container) {
            console.error('No existe #sankey');
            return;
        }

        if (!data) {
            console.error('No llegaron datos al Sankey');
            return;
        }

        if (!data.nodes || !data.edges) {
            console.error('Formato inválido:', data);
            return;
        }

        console.log('Nodes:', data.nodes);
        console.log('Edges:', data.edges);

        container.innerHTML = '';

        try {

            graph = new ApexSankey(container, options);

            graph.render({
                nodes: data.nodes,
                edges: data.edges
            });

        } catch (error) {

            console.error('Error renderizando ApexSankey:', error);

        }
    }

    Livewire.on('graphMaterial', function (data) {

        console.log('Evento graphMaterial recibido');

        renderSankey(data);

    });

    // IMPORTANTE:
    // El evento debe ejecutarse DESPUÉS de registrar el listener.
    @this.call('loadSankey');

});
</script>

@endpush
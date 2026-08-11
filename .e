-Comparativa anio anterior
	Grafica en ingresos
	Grafica en productos
	Grafica en servicios


-Por subproducto
    
    

-Gerencia
    kpis:
        Cantidades en proceso
        Valor del proceso
        Cantidades del inventario
        Valor del inventario
        Cantidades de materia prima
            Agrupado por: familia, unidad de medida y cantidad
        Valor de materia prima

        No. Ordenes
        No. Lotes
        Pendientes de captura (Backlog)
        Gasto



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

    Livewire.on('graphMaterial', (data) => {

        const container = document.getElementById('sankey');

        // Limpiar el contenedor
        container.innerHTML = '';

        graph = new ApexSankey(container, options);
        graph.render(data);

    });

});
</script>




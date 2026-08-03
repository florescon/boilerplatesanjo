@push('after-styles')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexsankey/dist/apexsankey.css">
@endpush

<div id="sankey" style="width:900px;height:600px;"></div>

@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/apexsankey"></script>

<script>
const graph = new ApexSankey(
    document.querySelector("#sankey"),
    {
        nodes: [
            {
                id: "Telas"
            },
            {
                id: "Kilo"
            }
        ],
        edges: [
            {
                source: "Telas",
                target: "Kilo",
                value: 100
            }
        ],

        width: 800,
        height: 500
    }
);

graph.render();
</script>
@endpush
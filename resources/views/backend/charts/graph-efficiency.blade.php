<div>
    <div
        id="gantt-container"
        style="width: 100%; height: 600px;"
    ></div>
</div>

@push('after-scripts')

<script src="https://cdn.jsdelivr.net/npm/apexgantt"></script>
<script>
    const tasks = @json($tasks);

    const gantt = new ApexGantt(
        document.getElementById('gantt-container'),
        {
            series: tasks,

            width: '100%',
            height: '600px',

            pixelsPerDay: 25.7,

            enableSelection: true,

            on: {
                taskClick: function (task) {
                    window.open(
                        '{{ route('admin.dashboard') }}',
                        '_blank'
                    );
                }
            }
        }
    );

    gantt.render();
</script>

@endpush
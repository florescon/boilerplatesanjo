@push('after-styles')

<style>

.tile{
    width: 100%;
    background:#fff;
    border-radius:5px;
    box-shadow:0px 2px 3px -1px rgba(151, 171, 187, 0.7);
    float:left;
    transform-style: preserve-3d;
    margin: 0px 5px;
}

.dates{
    border:1px solid #ebeff2;
    border-radius:5px;
    padding:50px 0px;
    margin:10px 20px;
    font-size:16px;
    color:#5aadef;
    font-weight:600;    
    overflow:auto;
}
.dates div{
    float:left;
    width:50%;
    text-align:center;
    position:relative;
}
.dates strong,
.stats strong{
    display:block;
    color:#adb8c2;
    font-size:11px;
    font-weight:700;
}
.dates span{
    width:1px;
    height:40px;
    position:absolute;
    right:0;
    top:0;  
    background:#ebeff2;
}
.stats{
    border-top:1px solid #ebeff2;
    background:#f7f8fa;
    overflow:auto;
    padding:15px 0;
    font-size:16px;
    color:#59687f;
    font-weight:600;
    border-radius: 0 0 5px 5px;
}
.stats div{
    border-right:1px solid #ebeff2;
    width: 25%;
    float:left;
    text-align:center
}

.stats div:nth-of-type(3){border:none;}

div.footer {
    text-align: right;
    position: relative;
    margin: 20px 5px;
}

div.footer a.Cbtn{
    padding: 10px 25px;
    background-color: #DADADA;
    color: #666;
    margin: 10px 2px;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: none;
    border-radius: 3px;
}

div.footer a.Cbtn-primary{
    background-color: #5AADF2;
    color: #FFF;
}

div.footer a.Cbtn-primary:hover{
    background-color: #7dbef5;
}

div.footer a.Cbtn-danger{
    background-color: #fc5a5a;
    color: #FFF;
}

div.footer a.Cbtn-danger:hover{
    background-color: #fd7676;
}

</style>
@endpush

<div>
    <div class="row mb-6">
        <div class="col-md-6">
            <select wire:model="periodo" class="form-control text-center select-azul">
                <option value="1sem">1 semana</option>
                <option value="4sem">4 semanas</option>
                <option value="8sem">8 semanas</option>
                <option value="12sem">12 semanas</option>
            </select>
        </div>

        <div class="col-md-6">
        </div>
    </div>


      <div class="row mt-4">
        <div class="col-md-6 bg-light shadow p-4">
            <div wire:ignore id="graphWeek"></div>
        </div>
        <div class="col-md-6">
            <div class="tile shadow ">
                <div class="wrapper">

                    <div class="dates">
                        <div class="start">
                            <strong>INICIA</strong> {{ now()->subYear()->format('d-m-y') }}
                            <span></span>
                        </div>
                        <div class="ends">
                            <strong>FINALIZA</strong> {{ $this->fechaInicio()->format('d-m-y') }}
                        </div>
                    </div>

                    
                    @foreach ($this->topProducts()->chunk(4) as $products)
                        <div class="stats">
                            @foreach ($products as $product)
                                <div>
                                    <strong>{{ $product->product_name }}</strong> {{ $product->product_total }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>
            </div> 

            <div class="tile shadow mt-4">
                <div class="wrapper">

                    <div class="dates">
                        <div class="start">
                            <strong>STARTS</strong> 12:30 JAN 2015
                            <span></span>
                        </div>
                        <div class="ends">
                            <strong>ENDS</strong> 14:30 JAN 2015
                        </div>
                    </div>

                    <div class="dates">
                        <div class="start">
                            <strong>STARTS</strong> 12:30 JAN 2015
                            <span></span>
                        </div>
                        <div class="ends">
                            <strong>ENDS</strong> 14:30 JAN 2015
                        </div>
                    </div>
                </div>
            </div> 

        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center bg-light mt-4 p-4">
          1 of 1
        </div>
      </div>

</div>

@push('after-scripts')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

    document.addEventListener('livewire:load', function () {


        let chart = new ApexCharts(
            document.querySelector("#graphWeek"),
                {
                chart:{
                    type:'heatmap',
                    height:700,
                },

                series:@json($series),
                title: {
                    text: 'Top 20 productos con mayor cantidades asignado a órdenes',
                    align: 'left',
                },
                subtitle: {
                    text: 'Primero contabiliza la mayor cantidad de los productos asignados a las órdenes, y los ordena.',
                    align: 'left',
                },
                plotOptions:{

                    heatmap:{

                      radius:12,

                      enableShades: false,

                        colorScale:{

                            ranges:[
                                {
                                    from:0,
                                    to:0,
                                    color:'#ECEFF1',
                                    name:'0'
                                },
                                {
                                    from:1,
                                    to:25,
                                    color:'#E3F2FD'
                                },
                                {
                                    from:26,
                                    to:50,
                                    color:'#90CAF9'
                                },
                                {
                                    from:51,
                                    to:100,
                                    color:'#42A5F5'
                                },
                                {
                                    from:101,
                                    to:200,
                                    color:'#1565C0'
                                },
                                {
                                    from:201,
                                    to:999999,
                                    color:'#002171'
                                }
                            ]
                        }

                    }

                },

                dataLabels:{
                    enabled:true
                },

                xaxis:{
                    type:'category'
                }
            }       
        );

        Livewire.on('graphWeek', function (series) {
            chart.updateSeries(series);
        });

        chart.render();

    });

    </script>

@endpush
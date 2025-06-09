@extends('backend.layouts.app')

@section('title', __('Station'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/chart.css') }}">
    <style type="text/css">
        .button {
          padding: 12px 20px;
          font-size: 1rem;
          text-align: center;
        }

        .icon {
          vertical-align: middle;
          font-size: 1.5rem;
        }

        .button-text {
          vertical-align: middle;
          margin-left: 0.3rem;
        }
    </style>

  <style>
    .scrolling-wrapper {
      overflow-x: auto;
      white-space: nowrap;
    }
    .card .yes {
      display: inline-block;
      width: 200px; /* Ancho de cada card */
      margin-right: 10px; /* Espacio entre cada card */
    }
    .columna {
      height: 500px; /* Altura fija para demostración, puedes ajustar según tus necesidades */
    }
  </style>


  <style type="text/css">
    .si ul li {
      margin-left: 15px;
      position: relative;
      padding-left: 5px;
    }
    .si ul li::before {
      content: " ";
      position: absolute;
      width: 1px;
      background-color: #000;
      top: 5px;
      bottom: -12px;
      left: -10px;
    }
    .si > ul > li:first-child::before {top: 12px;}
    .si ul li:not(:first-child):last-child::before {display: none;}
    .si ul li:only-child::before {
      display: list-item;
      content: " ";
      position: absolute;
      width: 1px;
      background-color: #000;
      top: 5px;
      bottom: 7px;
      height: 7px;
      left: -10px;
    }
    .si ul li::after {
      content: " ";
      position: absolute;
      left: -10px;
      width: 10px;
      height: 1px;
      background-color: #000;
      top: 12px;
    }

    .selected { 
      background-color: green; color:red; 
    }

    .selected option:checked {
      background-color: green; color:red; 
    }

  </style>

@endpush

@section('content')
    
  <livewire:backend.order.prod-batch :productionBatch="$productionBatch"/>

@endsection


@push('after-scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los inputs de cantidad
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    // Función para calcular el total
    function calculateTotal() {
        let total = 0;
        quantityInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        document.getElementById('total-sum').textContent = total;
    }
    
    // Agrega el evento input a cada campo
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Validar que no supere el máximo
            const max = parseFloat(this.dataset.max) || Infinity;
            if (parseFloat(this.value) > max) {
                this.value = max;
            }
            calculateTotal();
        });
    });
    
    // Calcula el total inicial
    calculateTotal();
});
</script>
@endpush

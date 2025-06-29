@extends('backend.layouts.app')

@section('title', __('Station'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/chart.css') }}">
    <style type="text/css">
        .btn-hover:focus {
            background-color: purple;
        }

        .btn-hover:hover {
            background-color: purple;
        }

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
    /* Estilo para el placeholder */
    input::placeholder {
        color: red !important;
        opacity: 1; /* Asegura que se muestre completamente opaco */
    }

    input.placeholder-zero::placeholder {
        color: gray !important;
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
    
  <livewire:backend.order.work-order :order="$order" :status="$status"/>

@endsection


@push('after-scripts')
  <script type="text/javascript">
      function redirect(goto) {
        var conf = confirm("¿Redireccionar?");
        if (conf && goto != '') {
          window.location = goto;
        }
      }

    var selectEl = document.getElementById('redirectSelect');

    selectEl.onchange = function() {
      if (this.value.startsWith('http')) {
        var goto = this.value;
        redirect(goto);
      }
    };
  </script>

<script>
  document.addEventListener('livewire:load', function() {
      Livewire.on('groupTotalUpdated', (parentId, total) => {
          document.getElementById(`total-${parentId}`).textContent = total;
      });
  });
</script>

  <script>
    $(document).ready(function() {
    $('input[type="number"]').each(function() {
        if($(this).attr('placeholder') === '0') {
            $(this).addClass('placeholder-zero');
        }
      });
    });
  </script>
@endpush

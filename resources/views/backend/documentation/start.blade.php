@extends('backend.documentation.documentation-layout')

@section('title', __('Quick start'))

@section('content')
  <div class="doc-body">
    <div class="doc-content">
      <div class="content-inner">
        <section id="features" class="doc-section">
          <h2 class="section-title">@lang('Features')</h2>
          <div class="section-block">

            <ul>
              <li>
                Control de acceso
                <ul>
                  <li>Iniciar sesión/Cerrar sesión/Restablecer contraseña.</li>
                  <li>Limitación de inicio de sesión.</li>
                  <li>Inicio de sesión único (cierre de sesión de todos los demás dispositivos).</li>
                  <li>Borrar sesión de usuario.</li>
                  <li>Caducidad de la contraseña.</li>
                  <li>
                    Gestión del administrador
                    <ul>
                      <li>Activar/Desactivar Usuarios.</li>
                      <li>Eliminación suave y permanente de usuarios.</li>
                      <li>Cambiar la contraseña de los usuarios.</li>
                      <li>Crear/Administrar Roles.</li>
                      <li>Administrar roles/permisos de usuarios.</li>
                      <li>Suplantar usuario.</li>
                      <li>Borrar sesión de usuario.</li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li>Panel de administración con el framework y/o paquete <a href="https://coreui.io" target="_blank">CoreUI</a>, <a href="http://getbootstrap.com/" target="_blank">Bootstrap 4</a>, <a href="https://fontawesome.com/" target="_blank">Font Awesome 5</a></li>
              <li><a href="https://github.com/ARCANEDEV/LogViewer" target="_blank">Visor de registros ARCANEDEV</A></li>

              <li>
                Departamentos
                <ul>
                  <li>Asociar el usuario es necesaria para la creación del departamento.</li>
                  <li>
                    Gestión de los departamentos.
                    <ul>
                      <li>Crear, editar y eliminar departamentos.</li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li>
                Órdenes/Pedidos
                <ul>
                  <li>Mostrar listado de órdenes, cotizaciones, ventas, salidas y todos los anteriores.</li>
                  <li>Seleccionar los órdenes/pedidos a exportar para resumirlos por producto general y realizar un concentrado. Posterior a eso en los detalles agrupar por producto y color, mostrando sólo las columnas de tallas involucradas y no la corrida completa.</li>
                  <li>Existe la posibilidad de imprimir mi orden/pedido, sin desglose, con iva desglosado, solamente los productos sin precios, todo esto en formato ticket y carta. Así como agrupar todo mi pedido por producto general y color, depués mostrarme cada talla particular.</li>
                  <li>Realizar pagos parciales o totales.</li>
                  <li>Modificar mi estado de entrega y consultar su historial.</li>
                  <li>Visualizar previamente los productos que tienen un consumo, esto es particular por producto y cada uno de sus atributos.</li>
                  <li>Agregar comentarios por cada uno de los productos de mi orden/pedido, y la posibilidad de agregar servicios una vez creado.</li>
                  <li>Efectuar consumo de materia prima por parte de mi asignación de mi primera estación de lote.</li>
                  <li>Trasladar automáticamente mis cantidades recibidas desde mi última estación del lote a mi primera estación de proceso de orden/pedido.</li>
                  <li>Consulta de seguimiento de mi pedido del lado del cliente.</li>
                </ul>
              </li>

              <li>
                Productos
                <ul>
                  <li>Crear producto general, donde obligatoriamente es necesario el atributo de color y talla para la creación de las combinaciones necesarias.</li>
                  <li>A partir del precio neto de compra definido, existe la posibilidad de crear los tipos de precios de acuerdo a la utilidad.</li>
                  <li>Creación de imagen principal, y particulares por color.</li>
                  <li>Definir un código único para el producto general, donde particularmente se hace uso del mismo junto con la codificación de atributos de color y talla.</li>
                  <li>Listado por producto general y por producto particular, en lo que también es posible visualizar las cantidades actuales.</li>
                  <li>Creación de etiqueta tanto como tamaño estándar y/o larga, así como una tercer que puede ser utilizada en el embalaje.</li>
                  <li>Kardex por producto particular y/o general.</li>
                  <li>Entrada y/o salida específicamente en cada uno de los productos particulares.</li>
                  <li>Asociar línea, marca, proveedor y modelo por producto general.</li>
                  <li>Asignar precio por parte de un servicio por producto general, haciendo distinción si es talla normal o talla extra.</li>
                  <li>Posibilidad de mover los productos entre inventarios.</li>
                  <li>Redactar información técnica, dimensiones, características y descripción, normas e información extra para que el cliente tenga la posibilidad de visualizarlo.</li>
                  <li>Asociar consumos generales y particulares por producto.</li>
                </ul>
              </li>

              <li>
                Servicios
                <ul>
                  <li>Crear, editar y elimiar servicios para asociarlos a ventas, cotizaciones, órdenes/pedidos.</li>
                </ul>
              </li>

              <li>
                Materias primas
                <ul>
                  <li>La creación de materia prima tiene como campos el costo de adquisición y la existencia al momento de realizar tal movimiento. Permite la creación de un código único.</li>
                  <li>Asociar unidad, color, proveedor y familia</li>
                  <li>Existe la posibilidad de filtrar por color, proveedor y familia.</li>
                  <li>Exportar listado de materia prima en archivo separado por comas y en formatos de Excel.</li>
                  <li>Cuenta con las herramientas dentro del listado
                    <ul>
                      <li>Historial de entradas y salidas.</li>
                      <li>Historial de entradas y salidas agrupado por fecha.
                      <li>Registros de materia prima consumidos</li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li>
                Explosión de materiales
                <ul>
                  <li>Haciendo uso de la selección de cada orden/pedido y/o cotización, se tiene la posiblidad de explosionar la materia prima de aquellos productos donde asocié los consumos requeridos.</li>
                  <li>Hecha la explosión, se cuenta con la posibilidad de visualizar y exportar los productos en desglose y agrupados, según sea la necesidad.</li>
                  <li>Exportar materias primas explosionadas en formato para el programa Excel, formatos PDF y CSV</li>
                </ul>
              </li>

              <li>
                Parámetros
                <ul>
                  <li>Para el correcto funcionamiento de mi aplicación, se es necesario la creación de la información base para la relación de mis demás módulos, esto es la creación, edición y eliminación de:
                    <ul>
                      <li>Colores</li>
                      <li>Tallas</li>
                      <li>Telas</li>
                      <li>Líneas</li>
                      <li>Unidades de medida</li>
                      <li>Marcas</li>
                      <li>Familias</li>
                      <li>Modelos</li>
                      <li>Proveedores</li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li>
                Tienda
                <ul>
                  <li>Los datos implicados en tienda, tienen de base descritos en los apartados anteriors: tanto en productos, cotizacione, pedidos y ventas correspondientes a sólo y exclusivamente lo que le concierne a la misma.</li>
                  <li>Crear e imprimir órdenes de servicio, asociando tipo de prenda y al personal que realiza tal servicio, a su vez haciendo ésto último se asocia en automático quién autoriza.</li>
                  <li>Ingresos y egresos necesarios para ver los movimientos de dinero, donde principalmente muestra sólo los no procesados en corte de caja. El historial de movimientos se puede visualizar y definirlo por rango de fecha.</li>
                  <li>Cortes de caja donde explícitamente muestra en formato ticket y carta, cuánto ha ingresado/egresado desde ahora hasta mi último corte, mostrando también las capturas de pedidos/órdenes asociados en ese rango fecha del corte.</li>
                </ul>
              </li>

              <li>
                Configuraciones
                <ul>
                  <li>Configuraciones generales para ajustar la información a mostrar al cliente, teléfono, correo, dirección, etc., así como editar IVA y porcentajes de utilidad dependiendo al tipo de precio.</li>
                  <li>Imágenes del banner, de marca y productos para visualizar en el apartado inicial del lado del cliente, así como la galería.</li>
                </ul>
              </li>

              <li>
                Hacer inventario
                <ul>
                  <li>Los inventarios requeridos para ver cuánta cantidad tengo de cada producto y/o materia prima donde tengo las cantidades capturadas, existencias y la diferencia que tengo entre ellas. Los inventarios se aplican a:

                    <ul>
                      <li>Producto Terminado.</li>
                      <li>Materia Prima.
                      <li>Tienda.</li>
                    </ul>

                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </section>

        <section id="screenshots" class="doc-section">
          <h2 class="section-title">Screenshots</h2>
          <div class="section-block">
            <div class="row">
              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-users.png') }}" data-title="Usuarios" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-users.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-users.png') }}" data-title="Usuarios" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-associated.png') }}" data-title="Productos asociados" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-associated.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-associated.png') }}" data-title="Productos asociados" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="clearfix"></div>

              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-bom.png') }}" data-title="Explosión de materiales" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-bom.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-bom.png') }}" data-title="Explosión de materiales" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-cash-out.png') }}" data-title="Corte de Caja" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-cash-out.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-cash-out.png') }}" data-title="Corte de Caja" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="clearfix"></div>

              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-inventory.png') }}" data-title="Inventario" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-inventory.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-inventory.png') }}" data-title="Inventario" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="col-md-6 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/screen-material-grouped.png') }}" data-title="Materia Prima agrupada" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/screen-material-grouped.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/screen-material-grouped.png') }}" data-title="Materia Prima agrupada" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>

              <div class="clearfix"></div>
            </div>
          </div><!--//section-block-->
        </section><!--//doc-section-->


        <section id="links" class="doc-section">

          <h2 class="section-title">Estructura del sitio</h2>
          <div class="section-block">

            <p>Hay ocasiones que no es necesaria la creación de un vínculo nuevo para realizar una acción, esto es por hacer mención al ejemplo de limpiar una sesión de usuario o desactivar un usuario. Estructura de vínculos posibles:</p>

            <div class="highlight">
              <pre tabindex="0" class="chroma">
                <code class="language-text" data-lang="text"><span class="line"><span class="cl">          dashboard/
                  </span></span><span class="line"><span class="cl">├── acceso/
                  </span></span><span class="line"><span class="cl">│   ├── usuarios/
                  </span></span><span class="line"><span class="cl">│   │   ├── crear usuario/
                  </span></span><span class="line"><span class="cl">│   │   ├── ver usuario/
                  </span></span><span class="line"><span class="cl">│   │   ├── editar usuario/
                  </span></span><span class="line"><span class="cl">│   │   ├── cambiar contraseña/
                  </span></span><span class="line"><span class="cl">│   │   └── exportar clientes/
                  </span></span><span class="line"><span class="cl">│   └── roles/
                  </span></span><span class="line"><span class="cl">│       ├── crear rol/
                  </span></span><span class="line"><span class="cl">│       └── editar rol/
                  </span></span><span class="line"><span class="cl">├── departamentos/
                  </span></span><span class="line"><span class="cl">│   └── crear departamento/
                  </span></span><span class="line"><span class="cl">├── listado órdenes/
                  </span></span><span class="line"><span class="cl">│   ├── agrupar y exportar órdenes/
                  </span></span><span class="line"><span class="cl">│   └── editar orden/
                  </span></span><span class="line"><span class="cl">│       ├── orden de servicio/
                  </span></span><span class="line"><span class="cl">│       ├── ver registros de pagos/
                  </span></span><span class="line"><span class="cl">│       ├── ver registros de entrega/
                  </span></span><span class="line"><span class="cl">│       ├── impresión ticket/
                  </span></span><span class="line"><span class="cl">│       ├── impresión carta/
                  </span></span><span class="line"><span class="cl">│       ├── estaciones de lote/
                  </span></span><span class="line"><span class="cl">│       ├── estaciones de proceso/
                  </span></span><span class="line"><span class="cl">│       ├── consumo puntual/
                  </span></span><span class="line"><span class="cl">│       ├── seguimiento de pedido/
                  </span></span><span class="line"><span class="cl">│       └── opciones avanzadas/
                  </span></span><span class="line"><span class="cl">├── captura orden/
                  </span></span><span class="line"><span class="cl">├── lotes/
                  </span></span><span class="line"><span class="cl">│   └── ver lote/
                  </span></span><span class="line"><span class="cl">├── productos/
                  </span></span><span class="line"><span class="cl">│   ├── crear producto/
                  </span></span><span class="line"><span class="cl">│   └── editar producto/
                  </span></span><span class="line"><span class="cl">│       ├── modificar consumo/
                  </span></span><span class="line"><span class="cl">│       ├── información avanzada/
                  </span></span><span class="line"><span class="cl">│       ├── precios y códigos/
                  </span></span><span class="line"><span class="cl">│       ├── imágenes/
                  </span></span><span class="line"><span class="cl">│       ├── mover entre inventarios/
                  </span></span><span class="line"><span class="cl">│       ├── eliminar atributos/
                  </span></span><span class="line"><span class="cl">│       ├── kardex/
                  </span></span><span class="line"><span class="cl">│       ├── impresión de etiquetas/
                  </span></span><span class="line"><span class="cl">│       └── lista de productos particulares/
                  </span></span><span class="line"><span class="cl">├── servicios/
                  </span></span><span class="line"><span class="cl">├── materias primas/
                  </span></span><span class="line"><span class="cl">│   ├── crear materia prima/
                  </span></span><span class="line"><span class="cl">│   ├── editar materia prima/
                  </span></span><span class="line"><span class="cl">│   └── impresión de etiqueta/
                  </span></span><span class="line"><span class="cl">├── explosión de materiales/
                  </span></span><span class="line"><span class="cl">├── parámetros/
                  </span></span><span class="line"><span class="cl">│   ├── colores/
                  </span></span><span class="line"><span class="cl">│   ├── tallas/
                  </span></span><span class="line"><span class="cl">│   ├── telas/
                  </span></span><span class="line"><span class="cl">│   ├── líneas/
                  </span></span><span class="line"><span class="cl">│   ├── unidades/
                  </span></span><span class="line"><span class="cl">│   ├── marcas/
                  </span></span><span class="line"><span class="cl">│   ├── familias/
                  </span></span><span class="line"><span class="cl">│   ├── modelos/
                  </span></span><span class="line"><span class="cl">│   └── proveedores/
                  </span></span><span class="line"><span class="cl">├── tienda/
                  </span></span><span class="line"><span class="cl">│   ├── principal/
                  </span></span><span class="line"><span class="cl">│   ├── productos/
                  </span></span><span class="line"><span class="cl">│   ├── cotización/
                  </span></span><span class="line"><span class="cl">│   ├── pedido/
                  </span></span><span class="line"><span class="cl">│   ├── venta/
                  </span></span><span class="line"><span class="cl">│   ├── salida de productos/
                  </span></span><span class="line"><span class="cl">│   ├── listado de pedidos/
                  </span></span><span class="line"><span class="cl">│   ├── listado de órdenes de servicio/
                  </span></span><span class="line"><span class="cl">│   │   ├── ver orden de servicio/
                  </span></span><span class="line"><span class="cl">│   │   └── impresión/
                  </span></span><span class="line"><span class="cl">│   ├── ingresos y egresos/
                  </span></span><span class="line"><span class="cl">│   │   └── impresión/
                  </span></span><span class="line"><span class="cl">│   └── corte de caja/
                  </span></span><span class="line"><span class="cl">│       └── historial de cortes de caja/
                  </span></span><span class="line"><span class="cl">│           └── ver corte de caja/
                  </span></span><span class="line"><span class="cl">├── configuraciones/
                  </span></span><span class="line"><span class="cl">│   ├── configuraciones generales/
                  </span></span><span class="line"><span class="cl">│   ├── imágenes del banner/
                  </span></span><span class="line"><span class="cl">│   ├── imágenes de marcas/
                  </span></span><span class="line"><span class="cl">│   ├── imágenes del producto/
                  </span></span><span class="line"><span class="cl">│   ├── galería/
                  </span></span><span class="line"><span class="cl">│   └── páginas/
                  </span></span><span class="line"><span class="cl">└── inventarios/
                  </span></span><span class="line"><span class="cl">    ├── producto terminado/
                  </span></span><span class="line"><span class="cl">    ├── materia prima/
                  </span></span><span class="line"><span class="cl">    └── tienda/
                  </span></span>
                </code>
              </pre>
            </div>

            <p>En este punto, todo está en el lugar correcto.</p>
          </div>

        </section><!--//doc-section-->

      </div><!--//content-inner-->
    </div><!--//doc-content-->


    <div class="doc-sidebar">
        <nav id="doc-nav">
            <ul id="doc-menu" class="nav doc-menu hidden-xs" data-spy="affix">
                <li><a class="scrollto" href="#features">Características</a></li>
                <li><a class="scrollto" href="#screenshots">Screenshots</a></li>
                <li><a class="scrollto" href="#links">Estructura del sitio</a></li>
            </ul><!--//doc-menu-->
        </nav>
    </div><!--//doc-sidebar-->

  </div><!--//doc-body-->              
@endsection

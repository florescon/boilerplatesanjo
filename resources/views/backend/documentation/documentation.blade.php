@extends('backend.documentation.documentation-layout')

@section('title', __('Documentation'))

@section('content')
  <div class="doc-body">
    <div class="doc-content">
      <div class="content-inner">
        <section id="users" class="doc-section">
          <h2 class="section-title">@lang('Users')</h2>
          <div class="section-block">

            <div class="code-block">
              <pre>
<code class="language-php">
1.  El tipo de usuario 'Usuario' está sin ningún permiso al acceso administrativo, no se le agrega
    ningún rol ni permiso adicional.
2.  Para la creación de un usuario es necesario nombre, dirección de correo electrónico y contraseña.
3.  La exportación de clientes sólo serán los que estén con el permiso de 'Usuario'.
</code>
              </pre>
            </div><!--//code-block-->

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/users.png') }}" data-title="Usuarios" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/users.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/users.png') }}" data-title="Usuarios" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>

          </div>
        </section>

        <section id="roles" class="doc-section">
          <h2 class="section-title">@lang('Roles')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Los roles son sólo asignables al tipo 'Administrador', donde es posible definir las categorías 
    totales de un tipo o parciales.
2.  Es posible generar N cantidad de roles y permisos, éste último viene dado por las características 
    útiles dentro de la aplicaćión, es decir, no es necesaria la creación de un permiso para hacer 
    modificaciones sobre el super administrador.
</code>
              </pre>
            </div><!--//code-block-->
          </div>
        </section>

        <section id="departaments" class="doc-section">
          <h2 class="section-title">@lang('Departaments')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Los departamentos se relacionan directamente con los registros de usuarios, es decir, 
    no se crea un departamento sin el vínculo del usuario.
2. A partir del anterior punto, también implica que un usuario puede tener múltiples departamentos.
</code>
              </pre>
            </div><!--//code-block-->

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/departaments.png') }}" data-title="Departamentos" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/departaments.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/departaments.png') }}" data-title="Departamentos" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>

          </div>
        </section>

        <section id="orders" class="doc-section">
          <h2 class="section-title">@lang('Orders')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Una vez creada la orden, no es posible asociar productos a la misma, sólo servicios.
2.  La cotización permite hacer cualesquier modificación tanto de a quién va dirigida, como 
    la modificación de productos y servicios.
3.  En todos los movimientos de órdenes, ventas, cotizaciones es posible la modificación de 
    los campos escritos con texto, es decir comentarios, observaciones, número de solicitud 
    y orden de compra, etc.
4.  Las órdenes/pedidos son los únicos permitidos para la creación de lotes.
4.  Las órdenes/pedidos son los únicos permitidos para visualización de los consumos previos.
</code>
              </pre>


            </div><!--//code-block-->

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/orders.png') }}" data-title="Órdenes" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/orders.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/orders.png') }}" data-title="Órdenes" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section id="products" class="doc-section">
          <h2 class="section-title">@lang('Products')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  A los productos es posible asociar colores y tallas, siendo estos los atributos obligatorios
    para la creación de un producto.
2.  Si existe ya un producto y está la posibilidad de crear un nuevo atributo, no es necesario
    crear el producto padre, basta con asociar únicamente el atributo. Ya que es el mismo producto
    e incluso la misma codificación base.
3.  La codificación de los subproductos se realiza con el código padre registrado en el producto
    general, sumado a sus atributos, esto es:
    Código general + Código color + Código talla
4.  El código general es único, ningún otro producto lo comparte, esto nos asegura la legibilidad
    de los productos y la consulta correcta.
</code>
              </pre>
            </div><!--//code-block-->

            <p>
              Crear un nuevo Producto, es posible en Productos > Crear. Para la creación de un Producto, los campos Nombre, Codigo, Colores, Tallas y Precio neto de compra, son obligatorios. Campos como Precio Menudeo, Precio Medio Mayoreo, Precio Mayoreo y Precio Especial serán datos que si bien son ingresables en los mismos, su omisión implica que serán llenados de manera automática. El parámetro para definir el precio será por el porcentaje asignado a la configuración para cada precio, esto se encuentra en Configuraciones > Configuraciones Generales.
            </p>
            <div class="screenshot-holder">
              <a href="{{ asset('docs/img/documentation/new-product.png') }}" data-title="Nuevo Producto" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/new-product.png') }}" alt="screenshot"></a>
              <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/new-product.png') }}" data-title="Nuevo Producto" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
            </div>

            <p>
              Editar los precios de un Producto, encontrado en Productos > Seleccionar Producto > Precios y códigos. Para la edición de los Precios, es necesario seleccionar el checkbox 'Precios', ello nos permite modificar las variantes de precios que tengamos del producto seleccionado. Precio de Proveedor, Precio Menudeo, Precio Medio Mayoreo, Precio Mayoreo, Precio Especial, son precios modificables, todos ellos sin IVA. El primer campo es Precio de Proveedor, el cual automaticamente me llena el resto de campos dependiendo de los valores en porcentaje asignados en Configuraciones > Configuraciones Generales. Idealmente hay que llenar solamente el Precio de Proveedor para posteriormente guardar con 'Guardar precio proveedor y todos los precios', aunque no es obligatorio, puesto que es posible ingresar los diferentes campos y solamente guardar el valor que se necesite.
            </p>


            <div class="screenshot-holder">
              <a href="{{ asset('docs/img/documentation/edit-product.png') }}" data-title="Editar Precios Producto" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/edit-product.png') }}" alt="screenshot"></a>
              <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/edit-product.png') }}" data-title="Editar Precios Producto" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
            </div>
            
            <p>
              Cada tipo de precio exceptuando el Precio de Proveedor se permite actualizar por tallas y colores.
            </p>

            <div class="screenshot-holder">
              <a href="{{ asset('docs/img/documentation/edit-price.png') }}" data-title="Editar Precios" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/edit-price.png') }}" alt="screenshot"></a>
              <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/edit-price.png') }}" data-title="Editar Precios" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
            </div>


          </div>
        </section>

        <section id="services" class="doc-section">
          <h2 class="section-title">@lang('Services')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Los servicios son volátiles en cuanto al precio establecido desde un inicio, por lo que son
    fuera de la cotización, posibles de modificar el precio, de acuerdo a las variantes de tiempo,
    dimensiones, tipo, etc., que hacen posible tal volatilidad.
</code>
              </pre>
            </div><!--//code-block-->

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/service.png') }}" data-title="Servicios" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/service.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/service.png') }}" data-title="Servicios" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>

          </div>
        </section>

        <section id="feedstocks" class="doc-section">
          <h2 class="section-title">@lang('Feedstocks')</h2>
          <div class="section-block">

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/feedstock.png') }}" data-title="Materia Prima" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/feedstock.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/feedstock.png') }}" data-title="Materia Prima" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>

          </div>
        </section>


        <section id="pre-invoice" class="doc-section">
          <h2 class="section-title">@lang('Prefactura confección')</h2>
          <div class="section-block">

            <p>
              La prefactura de Confección se generará dentro de la sección Estaciones. Seleccionar la Estación 'Confección' para enlistar lo relaccionado a esa estación, este valor junto con elegir Personal y Rango de Fecha me habilitán los botones de 'Exportar histórico' y 'Exportar histórico, agrupado'
            </p>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/preinvoice.png') }}" data-title="Prefactura" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/preinvoice.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/preinvoice.png') }}" data-title="Prefactura" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>

          </div>
        </section>

        <section id="bom" class="doc-section">
          <h2 class="section-title">@lang('Bom of Materials')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Explosionar las órdenes generará los consumos previstos solamente de aquellos que el consumo
    agregado a los productos esté.
2.  La explosión de materiales no sólo es acerca de los consumos previstos de las órdenes 
    seleccionadas sino también incluye el concentrado de los productos implicados para tal 
    explosión.
</code>
              </pre>
            </div><!--//code-block-->

          </div>
        </section>

        <section id="parameters" class="doc-section">
          <h2 class="section-title">@lang('Parameters')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Antes que cualquier registro de materia prima, producto o pedido, será necesario la asignación
    de los parámetros base, como lo son los atributos de color y talla, unidades de medida y talla.
</code>
              </pre>
            </div><!--//code-block-->

          </div>
        </section>

        <section id="provider" class="doc-section">
          <h3>
            <ul><li>@lang('Vendors')</li></ul>
          </h3>
          <div class="section-block">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <p>
                  Crear un nuevo Proveedor esta dentro de los 'Parámetros', es ingresando a Parámetros > Proveedores > Crear Proveedor.
                  En el que los campos Nombre, Nombre Corto, Email, Y Ciudad son obligatorios.

                </p>
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/new-provider.png') }}" data-title="Nuevo Proveedor" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/new-provider.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/new-provider.png') }}" data-title="Nuevo Proveedor" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section id="store" class="doc-section">
          <h2 class="section-title">@lang('Store')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Tienda es el apartado que comparte con los módulos principales. Comparte más no mezcla
    la información que se requiera del apartado principal y no filtra la información que 
    no le compete.
2.  En el módulo de Tienda sólo están disponibles Órdenes de Servicio, Ingresos y Egresos, Corte 
    de caja.
</code>
              </pre>
            </div><!--//code-block-->

          </div>
        </section>

        <section id="service-order" class="doc-section">
          <h3>
            <ul><li>@lang('Service Order')</li></ul>
          </h3>
          <div class="section-block">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <p>
                  Para las órdenes de servicio se requiere el tipo de servicio y la imagen. Datos como las dimensiones, archivo y comentario general son opcionales, aunque se re comienda por lo que son útiles para ser más explícitos.

                </p>
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/service-order.png') }}" data-title="Orden de Servicio" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/service-order.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/service-order.png') }}" data-title="Orden de Servicio" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section id="incomes-and-expenses" class="doc-section">
          <h3>
            <ul><li>@lang('Incomes and expenses')</li></ul>
          </h3>
          <div class="section-block">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <p>
                  Los ingresos y egresos necesarios para la administración de los movimientos del flujo de dinero.
                  Para crear un ingreso o egreso es obligatorio el nombre del movimiento, cantidad y método de pago. La distinción de un ingreso o egreso es que este último se da en la casilla de verificación 'Quiero que sea egreso' donde se corrobora que efectivamente el movimiento se guardará con el botón último 'Guardar egreso'.
                </p>
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/create-income.png') }}" data-title="Crear ingreso/egreso" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/create-income.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/create-income.png') }}" data-title="Crear ingreso/egreso" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
                <p>
                  El listado de ingresos y egresos muestra por defecto sólo los movimientos no asignados a ningún corte de caja, es decir, los últimos creados desde el último corte de caja. Existen filtros que seleccionando cada uno de los recuadros ingresos y egresos, azul y rojo respectivamente, me filtra sólo el que haya seleccionado. Otro filtro muy importante es el botón de historial, que muestra el listado completo con o sin corte asignado. La entrada rango de fecha y los botones por 'mes', 'semana actual' y 'hoy' son algunos otros filtros más en los que mostrará dependiendo de cuál haya sido la selección.
                </p>
              </div>
            </div>
          </div>
        </section>
        <section id="daily-cash-closing" class="doc-section">
          <h3>
            <ul><li>@lang('Daily cash closing')</li></ul>
          </h3>
          <div class="section-block">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-sm-12">
                <p>
                  La condición del corte de caja es que independientemente del tiempo y número de registros, se asociará un corte de caja desde que el anterior corte se ha creado. Los cortes implican egresos y egresos, movimientos de órdenes/ventas.
                  El balance es la diferencia entre solanmente ingresos y egresos, y que como dato secundario es la cantidad inicial.
                </p>
                <div class="screenshot-holder">
                  <a href="{{ asset('docs/img/documentation/box.png') }}" data-title="Corte de caja" data-toggle="lightbox" class="hoverZoomLink"><img class="img-responsive" src="{{ asset('docs/img/documentation/box.png') }}" alt="screenshot"></a>
                  <a class="mask hoverZoomLink" href="{{ asset('docs/img/documentation/box.png') }}" data-title="Corte de caja" data-toggle="lightbox"><i class="icon fa fa-search-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section id="settings" class="doc-section">
          <h2 class="section-title">@lang('Settings')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Los tipos de precios son obligatorios en la configuraciones generales, útiles para
    el correcto desempeño de la aplicación.
</code>
              </pre>
            </div><!--//code-block-->

          </div>
        </section>

        <section id="inventories" class="doc-section">
          <h2 class="section-title">@lang('Inventories')</h2>
          <div class="section-block">
            <div class="code-block">
              <pre>
<code class="language-php">
1.  Los inventarios para mayor rapidez se escanean con su QR, o como segunda alternativa se busca
    el producto y se coteja el nombre verificando el código sea el correspondiente.
</code>
              </pre>
            </div><!--//code-block-->

          </div>
        </section>

      </div>
    </div>

    <div class="doc-sidebar">
        <nav id="doc-nav">
            <ul id="doc-menu" class="nav doc-menu hidden-xs" data-spy="affix">
                <li><a class="scrollto" href="#users">Usuarios</a></li>
                <li><a class="scrollto" href="#roles">Roles</a></li>
                <li><a class="scrollto" href="#departaments">Departamentos</a></li>
                <li><a class="scrollto" href="#orders">Órdenes</a></li>
                <li><a class="scrollto" href="#products">Productos</a></li>
                <li><a class="scrollto" href="#services">Servicios</a></li>
                <li><a class="scrollto" href="#pre-invoice">Prefactura Confección</a></li>
                <li><a class="scrollto" href="#feedstocks">Materias Primas</a></li>
                <li><a class="scrollto" href="#bom">Explosión de materiales</a></li>
                <li>
                  <a class="scrollto" href="#parameters">Parámetros</a>
                  <ul>
                    <li><a class="scrollto" href="#provider">Proveedores</a></li>
                  </ul>
                </li>
                <li>
                  <a class="scrollto" href="#store">Tienda</a>
                  <ul>
                    <li><a class="scrollto" href="#service-order">Orden de Servicio</a></li>
                    <li><a class="scrollto" href="#incomes-and-expenses">Ingresos y Egresos</a></li>
                    <li><a class="scrollto" href="#daily-cash-closing">Corte de caja</a></li>
                  </ul>
                </li>
                <li><a class="scrollto" href="#settings">Configuraciones</a></li>
                <li><a class="scrollto" href="#inventories">Inventarios</a></li>
            </ul><!--//doc-menu-->
        </nav>
    </div><!--//doc-sidebar-->

  </div><!--//doc-body-->
@endsection

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar proyectos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar proyectos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <?php 

          if($_SESSION["perfil"] == "Administrador"){

                          echo '<button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProyecto">
          
          Agregar proyecto

        </button>';

                        }

        ?>
  
        

      </div>

      <div class="box-body">
        
       <table id="tabla_proyectos" class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Proyecto</th>           
           <th style="width:10px">Etapa</th>
           <th>Estado</th>
           <th>Terreno</th>
           <th>Precio Lista($)</th>
           <th>Area(m2)</th>
           <th>Fecha Separacion</th>
           <?php if($_SESSION["perfil"] == "Administrador")
            {
            echo '<th style="width:10px">Acciones</th>';
            }
           ?>

         </tr> 

        </thead>

        <tbody>

        <?php

          $item = null;
          $valor = null;
          $proyectos = ControladorProyectos::ctrMostrarProyectos($item, $valor);

          foreach ($proyectos as $key => $value) {
           
            echo ' <tr>

                    <td>'.($key+1).'</td>

                    <td class="text-uppercase">'.$value["proyecto"].'</td>

                    <td class="text-uppercase">'.$value["etapa"].'</td>';
                    if($_SESSION["perfil"] == "Administrador")
                    {
            if($value["estado"] != 0)
                    {
            echo '    <td><button class="btn btn-danger btn-xs btnLiberar" idProyecto="'.$value["id_proyecto"].'" estadoProyecto="0">Reservado</button></td>';
                    }
                    else
                    {

            echo '     <td><button class="btn btn-success btn-xs">Libre</button></td>';

                  }
                    }
                    else
                    {
                      if($value["estado"] != 0)
                      {
            echo '    <td><button class="btn btn-danger btn-xs">Reservado</button></td>';
                    }
                    else
                    {

            echo '     <td><button class="btn btn-success btn-xs">Libre</button></td>';

                  }
                    }
            echo '          <td class="text-uppercase">'.$value["terreno"].'</td>

                    <td class="text-uppercase">$ '.$value["precio_lista"].'</td>
                    <td class="text-uppercase">'.$value["area"].'</td>
                    <td class="text-uppercase">'.$value["fecha_separacion"].'</td>';
                    if($_SESSION["perfil"] == "Administrador"){

                          echo '<td><button class="btn btn-danger btnEliminarProyecto" idProyecto="'.$value["id_proyecto"].'"><i class="fa fa-times"></i></button></td>';

                        }


          echo '    </tr>';
          }

        ?>

        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>
<!--=====================================
MODAL AGREGAR CATEGORÍA
======================================-->

<div id="modalAgregarProyecto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar proyecto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control" id="nuevoProyecto" name="nuevoProyecto" required>
                    <option value="">Seleccionar Proyecto </option>
                   <option value="Bahia Azul">Bahia Azul</option>
                   <option value="Montesol">Montesol</option>
                   <option value="Tulipanes">Tulipanes</option>
                   </select>

              </div>

            </div>
             <!-- ENTRADA PARA EL NOMBRE PROYECTO -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <select class="form-control" id="etapaProyecto" name="etapaProyecto" required>
                   <option value="">Seleccionar Etapa </option>
                   <option value="1">1</option>
                   </select>

              </div>

            </div>
             <!-- ENTRADA PARA EL USUARIO -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="terrenoProyecto" placeholder="Ingresar Terreno" id="terrenoProyecto" required>

              </div>

            </div>
             <!-- ENTRADA PARA EL USUARIO -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="precioProyecto" placeholder="Ingresar Precio" id="precioProyecto" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL USUARIO -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="areaProyecto" placeholder="Ingresar Area" id="areaProyecto" required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar proyecto</button>

        </div>

        <?php

          $crearProyecto = new ControladorProyectos();
          $crearProyecto -> ctrCrearProyecto();

        ?>

      </form>

    </div>

  </div>

</div>
<!--=====================================
MODAL EDITAR CATEGORÍA
======================================-->

<div id="modalEditarProyecto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar proyecto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <input type="text" class="form-control input-lg" name="editarProyecto" id="editarProyecto" required>

                 <input type="hidden"  name="idProyecto" id="idProyecto" required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>

      <?php

          $editarProyecto = new ControladorProyectos();
          $editarProyecto -> ctrEditarProyecto();

        ?> 

      </form>

    </div>

  </div>

</div>

<?php

  $borrarProyecto = new ControladorProyectos();
  $borrarProyecto -> ctrBorrarProyecto();

?>
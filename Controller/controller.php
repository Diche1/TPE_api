<?php

require_once(PATH . '/Model/motoresModel.php');
require_once(PATH . '/View/view.php');

class motoresControlador
{
    private $model;
    private $view;
    private $ordenarPor;
    private $orden;
    private $buscar;
    private $page;
    private $limit;
    private $inicio;
    public function __contruct()
    {
        $this->model = new ModelApi;
        $this->view = new APIView;
    }
    public function obtenerParametros()
    {
        $this->ordenarPor = isset($_GET["ordenarPor"]) ? $_GET["ordenarPor"] : null;
        $this->orden = isset($_GET["orden"]) ? $_GET["orden"] : "asc";
        $this->buscar = isset($_GET["buscar"]) ? $_GET["buscar"] : null;
        // Pagina a mostrar.
        $this->page = isset($_GET["page"]) ? $_GET["page"] : 1;
        //cantidad a mostrar 3 por defecto
        $this->limit = isset($_GET["limit"]) ? $_GET["limit"] : 3;
        // calculo paginado - desplazamiento
        $this->inicio = $this->limit * $this->page - $this->limit;
    }
    private function obtenerDatos()
    {
        $parametros = file_get_contents("php://input");
        return json_decode($parametros);

    }

    /*     El servicio que lista una colección entera debe poder ordenarse opcionalmente por al
        menos un campo de la tabla, de manera ascendente o descendente  */
    function obtenerProductos()
    {
        $this->obtenerParametros();
        //        Verifico si se proporciona campo y criterio de ordenamiento ( ascendente o descendete)
        if (!empty($this->ordenarPor) && !empty($this->orden)) {
            // Buscar todos los artículos ordenados
            $articulos = $this->model->obtenerProductos($this->ordenarPor, $this->orden, $this->inicio, $this->limit, $this->buscar);
        } else {
            // Si no, usar el valor predeterminado ( ascendente )
            $this->ordenarPor == null ? $this->ordenarPor = "Id" : $this->ordenarPor;
            $articles = $this->model->obtenerProductos($this->ordenarPor, "asc", $this->inicio, $this->limit, $this->buscar);
        }
        if ($articulos) {
            $this->view->response($articulos, 200);
        } // No Existe
        else {
            $this->view->response("No hay articulos!", 404);

        }
    }
    function obtenerProductosOrd($parametros)
    {
        $this->obtenerParametros();
        $orden = $parametros[":orden"];
        if ($orden != "desc" && $orden != "asc") {
            $orden = "asc";
        }
        // Buscar TODOS los productos de forma Ordenada
        $articulos = $this->model->obtenerProductos("Potencia", $orden, $this->inicio, $this->limit, $this->buscar);
        // existen, se muestran :
        if ($articulos) {
            $this->view->response($articulos, 200);
        }
        //No existen, Muestra el Mensaje.
        else {
            $this->view->response("No hay productos!.", 404);
        }

    }
    function obtenerCategorias()
    {
        $this->obtenerParametros();
        $this->ordenarPor == null ? $this->ordenarPor = "Id" : $this->ordenarPor;
        //Obtener categorias--- Buscarlas.
        $categorias = $this->model->obtenerMarcas($this->ordenarPor, $this->orden, $this->inicio, $this->limit, $this->buscar);
        if ($categorias) {
            //Mostrar
            $this->view->response($categorias, 200);
        }
        //No existen, mostrar mensaje
        else {
            $this->view->response("No hay productos!.", 404);
        }
    }
    function obtenerDetallesProd($parametros)
    {
        // Buscar Detalles de Productos
        $id = $parametros[":id"];
        $detalles = $this->model->obtenerDetalles($id);
        if ($detalles) {
            //mostrar
            $this->view->response($detalles, 200);
        } else {
            $this->view->response("No hay productos!.", 404);

        }
    }

    function modificarProducto($id)
    {
        if ($this->validar()) {
            // de alguna forma preguntar si es admin

            $parametros = $this->obtenerDatos();
            //verificar que completa todos los datos
            if (
                !empty($parametros) &&
                isset($parametros->id_marca) &&
                isset($parametros->potencia) &&
                isset($parametros->velocidad) &&
                isset($parametros->voltaje) &&
                isset($parametros->frecuencia)
            ) {

                $id_marca = $parametros->id_marca;
                $potencia = $parametros->potencia;
                $velocidad = $parametros->velocidad;
                $voltaje = $parametros->voltaje;
                $frecuencia = $parametros->frecuencia;

                // Añado el producto a la BD.
                $this->model->editProductos($id, $id_marca, $potencia, $velocidad, $voltaje, $frecuencia);
                $this->view->response("el articulo $id fue colocado correctamente", 201);
            } else {
                $this->view->response("Ocurrio un error.", 404);
            }
        } else {
            //no tiene permisos
            $this->view->response("No tiene permisos para realizar esta accion.", 401);
        }
    }
    function modificarMarcas()
    {
        if ($this->validar()) {
            // de alguna forma preguntar si es admin

            $parametros = $this->obtenerDatos();
            //verificamos que el post no este vacio y traiga todo.
            if (!empty($parametros) && isset($parametros->Fabricante) && isset($parametros->Id)) {
                $fabricante = $parametros->Fabricante;
                $id = $parametros->id;
                $this->model->editMarcas($id, $fabricante);
                $this->view->response("La categoria $id fue modificada con exito, ahora se llama $fabricante.", 201);

            } else {
                //SI FALTA ALGUN DATO, NO ANDA
                $this->view->response("Ha ocurrido un error", 400);
            }
        } else //NO TIENE PERMISOS
            $this->view->response("No tiene permisos para realizar esta acción", 401);
    }



// falta los agregar productos y marcas.

// generar el apikey 


    function validar()
    {
        $existe = false;
        if (isset($_SERVER['HTTP_APIKEY'])) {
            $clave = $_SERVER['HTTP_APIKEY'];
            $existe = $this->model->isValidApiKey($clave);
        }
        return $existe;
    }
}


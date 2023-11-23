<?php
//defino las URL de la raiz para poder utilizar URL semanticas correctamente
/* define('PATH', dirname(__FILE__));
define('BASE_URL', '//' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']) . '/'); */
//relaciono al controlador
require_once(PATH . '/libs/Router.php');
require_once(PATH . '/Controller/controller.php');

//crea el router
$router = new Router();

// ---------------     defino la tabla de ruteo --------------------

// -------------        tabla de Ruteo : ------- 

$router->addRoute('productos','GET','controller','obtenerProductos');
//          Consigna obligatoria 3 TPE
//obtener productos ordenados por potencia (ascendente o descendente)
$router->addRoute('productosOrdenados/:orden','GET','Controller','obtenerProductosOrd');
// ---- detalles de un PRODUCTO ----
$router->addRoute('productos/:id','GET','Controller','obtenerDetallesProd');
//añadir articulo (requiere autenticacion por ApiKey)
$router->addRoute('productos','POST','Controller','');
//editar articulo (requiere autenticacion por ApiKey)
$router->addRoute('productos','PUT','Controller','setArticle');
//obtener marcas
$router->addRoute('marcas','GET','Controller','getCategories');
//obtener productos de una marca
$router->addRoute('marcas/:tipo','GET','Controller','getArticlesByCategoryName');
//añadir categoria 
$router->addRoute('marcas','POST','Controller','addCategory');
//editar categoria 
$router->addRoute('marcas','PUT','Controller','setCategory');

// ---------------- Rutea -----------------
$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
?>


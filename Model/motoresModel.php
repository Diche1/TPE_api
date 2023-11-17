<?php

class ModelApi
{

    /* protected $db;
    private $adminUserPw = '$2y$10$FF6r02TU9nzHXMJwm6jEo.UpI6RWVVuNc.kFZrAh9PIcADEcp2v2u';
    private $adminApiKey = '$2y$10$w6ATQYSLhFjFPaEbCIa3UuASUAaEehwsl4msp4FrjfRAfdKZ8nS1y'; */
    private $PDO;
    /*                  Creamos el contructor PDO, para reutilizar               */
    function __construct()
    {
        $this->PDO = new PDO('mysql:host=localhost;dbname=db_motores;charset=utf8', 'root', '');
        /* llamamos a desplegarTablas */
        $this->desplegarTablas();

    }

    function desplegarTablas()
    {
        //Verifica si hay tablas
        $query = $this->PDO->query('VER TABLAS');
        $tablas = $query->fetchAll();
        if (count($tablas) == 0) {
            // Si no hay tablas, CREARLAS
        }
    }


    //  Obtengo Productos + Fabricante ( tabla marcas )
    function obtenerProductos($inicio, $cantidad, $buscar, $orden, $campo)
    {
        $sentencia = "SELECT p.Potencia,p.Velocidad,m.Fabricante 
                FROM productos AS p 
                INNER JOIN marcas AS m 
                ON p.Id_marca = m.Id";

        if ($buscar != null) {
            $sentencia .= "WHERE m.Fabricante LIKE '%$buscar%'";
        }
        $sentencia .= "ORDEN BY $campo $orden LIMIT $cantidad OFFSET $inicio";
        $query = $this->PDO->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    //      Obtengo Productos por ID ( Mas detalles )
    function obtenerDetalles($item_id)
    {
        $sentencia = "SELECT p.*, m.Fabricante 
                FROM productos AS p 
                INNER JOIN marcas AS m 
                ON p.Id_marca = m.Id 
                WHERE p.Id=?";

        $query = $this->PDO->prepare($sentencia);
        $query->execute([$item_id]);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    //     Obtengo Marcas ( o categorias )
    function obtenerMarcas($campo, $orden, $inicio, $cantidad, $buscar)
    {
        $sentencia = "SELECT * 
                FROM marcas";

        if ($buscar != null) {
            $sentencia .= "WHERE tipo LIKE '%$buscar%'";
        }
        $sentencia .= "ORDER BY $campo $orden LIMIT $cantidad OFFSET $inicio";
        $query = $this->PDO->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    // PAGINADO DE CATEGORIA 
    function obtenerMarcasPag($inicio, $cantidad)
    {
        $sentencia = "SELECT * 
                FROM marcas";
        $query = $this->PDO->prepare($sentencia);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;

    }


    //-------------------------
// Editar Productos

    function editProductos($id, $id_marca, $potencia, $velocidad, $voltaje, $frecuencia)
    {
        $sentencia = "UPDATE producto 
                SET Id_marca=?, Potencia=?, Velocidad=?, Voltaje=?, Frecuencia=? 
                WHERE Id=?";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$id_marca, $potencia, $velocidad, $voltaje, $frecuencia, $id]);
    }

    // Editar Marcas 

    function editMarcas($id, $fabricante)
    {
        $sentencia = "UPDATE marcas 
                SET fabricante=? 
                WHERE=Id=?";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$fabricante, $id]);
    }

    // Remover Productos

    function quitarProductos($id)
    {
        $sentencia = "DELETE FROM productos 
                WHERE Id=?";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$id]);
    }

    // REMOVER MARCAS
    function quitarMarcas($id)
    {
        $sentencia = "DELETE FROM marcas 
                WHERE Id=?";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$id]);
    }

    // AGREGAR PRODUCTOS 
    function añadirProductos($id_marca, $potencia, $velocidad, $voltaje, $frecuencia)
    {
        $sentencia = "INSERT INTO productos (Id_marca,Potencia,Velocidad,Voltaje,Frecuencia)
                VALUES (?,?,?,?,?)";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$id_marca, $potencia, $velocidad, $voltaje, $frecuencia]);
    }
    // AGREGAR MARCAS 

    function añadirMarcas($fabricante)
    {
        $sentencia = "INSERT INTO marcas(Fabricante)
                VALUES (?)";
        $query = $this->PDO->prepare($sentencia);
        $query->execute([$fabricante]);
    }
}
?>
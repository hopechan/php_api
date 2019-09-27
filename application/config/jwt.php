<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    /**
     * Esta clave se utiliza para firmar los token esto para evitar
     * que alguien quiera utilizar token maliciosos
     * En un entorno de produccion es mejor una cadena con caracteres aleatorios
     */
    $config['jwt_key'] = 'mi llave secreta';
    //duracion de vida del token en minutos
    $config['token_timeout'] = 60;
?>
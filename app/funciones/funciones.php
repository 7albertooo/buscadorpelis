<?php

function sanear($dato) {

    // Eliminar espacios en blanco y convertir caracteres especiales
    return htmlspecialchars(trim($dato));
}

function validarUser($username) {
      //Validar que el nombre de usuario no esta vacio
      if(empty($username)) {
          return "El nombre de usuario no puede estar vacio.";
      }

      if(strlen($username) < 3 || strlen($username) > 20) {
          return "El nombre de usuario debe tener entre 3 y 20 caracteres.";
      }
}

function validarEmail($email) {

    //Validar que el email no esta vacio
    if(empty($email)) {
        return "El email no puede estar vacio.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "El email no es valido.";
    }
}


function validarPassword($password) {

    //Validar que la contraseña no esta vacia
    if(empty($password)) {
        return "La contraseña no puede estar vacia.";
    }

    if(strlen($password) < 6) {
        return "La contraseña debe tener al menos 6 caracteres.";
    }
}

function validarFormulario($datos) {

    $errores = [];

    if($error = validarUser($datos['username'])) {
        $errores[] = $error;
    }

    if($error = validarEmail($datos['email'])) {
        $errores[] = $error;
    }

    if($error = validarPassword($datos['password'])) {
        $errores[] = $error;
    }

    return $errores;

}

?>
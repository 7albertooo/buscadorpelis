<?php


function buscador($titulo, $apiKey) {

    // Formatear el título para la URL
    $titulo = urlencode($titulo);

    // Construir la URL de la API
    $url = "https://www.omdbapi.com/?t=$titulo&apikey=$apiKey";

    // Hacer la solicitud a la API
    $response = @file_get_contents($url);

    // Verificar si la respuesta es válida
    if ($response !== false) {
        // Decodificar la respuesta JSON
        return json_decode($response, true);
    }

    // En caso de error, devolver null
    return null;
}

function peliculasRecomendadas($apiKey) {

    $titulos = ["Zootopia 2", "Jurassic World: Rebirth", "Avatar: The Way of Water", "Five Nights at Freddy's 2"];
    $recomendadas = [];

    // Obtener datos para cada título
    foreach ($titulos as $titulo) {
        $data = buscador($titulo, $apiKey); 
        if ($data) {
            $recomendadas[] = $data;
        }
    }

    return $recomendadas;
}



?>
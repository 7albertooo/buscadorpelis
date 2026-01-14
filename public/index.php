<?php
require_once __DIR__ . '/../app/funciones/session.php';
include_once __DIR__ . '/../app/controladores/buscador.php';
include_once __DIR__ . '/../app/funciones/funcionesBuscador.php';

$apiKey = "e6f96b74";


$reco_ttl = 60; 

// Forzar refresco manual con refresh=1
$force_refresh = isset($_GET['refresh']) && $_GET['refresh'] == '1';

// Regenerar si no existe, si expiró o si se fuerza refresco
$need_refresh = $force_refresh || !isset($_SESSION['recomendadas']) || !isset($_SESSION['recomendadas_ts']) || (time() - intval($_SESSION['recomendadas_ts']) > $reco_ttl);
if ($need_refresh) {
    $_SESSION['recomendadas'] = peliculasRecomendadas($apiKey);
    $_SESSION['recomendadas_ts'] = time();
}
$recomendadas = $_SESSION['recomendadas'];


$data = $_SESSION['data'] ?? null;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/icon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kablammo&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap');
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include_once "header.php"; ?>

    <div class="main-index container app-main d-flex flex-column justify-content-center align-items-center">

        <div class="titulo text-center mb-4">
            <h1>Bienvenido a FSERCH</h1>
            <p>Encuentra lo que amas, comenta lo que disfrutas</p>
        </div>

        <form class="search-box mb-4" method="POST" action="index.php">
            <?php echo csrf_input(); ?>
            <div class="input-group shadow">
                <input class="form-control" type="search" name="titulo" placeholder="Buscar película..." autocomplete="off" required>
                <button class="btn btn-netflix" type="submit">Buscar</button>
            </div>
        </form>


       
        <?php if ($data && isset($data['Response']) && $data['Response'] === "True"): ?>

            <div class="card shadow position-relative movie-card" style="max-width: 600px;">
                <form method="POST" action="index.php" style="position:absolute; top:0; right:0; margin:8px;">
                    <?php echo csrf_input(); ?>
                    <input type="hidden" name="clear_data" value="1">
                    <button type="submit" class="btn-close" aria-label="Close"></button>
                </form>

                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?= $data['Poster']; ?>" class="img-fluid rounded-start" alt="Poster">
                    </div>

                    <div class="col-md-8">

                        <div class="card-body">
                            <h5 class="card-title"><?= $data['Title']; ?></h5>
                            <p class="card-text"><?= $data['Plot']; ?></p>
                            <p class="card-text">
                                <small class="text-muted-light">Genero: <?= $data['Genre']; ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted-light">Duración: <?= $data['Runtime']; ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted-light">Publicación: <?= $data['Released']; ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted-light">Director: <?= $data['Director']; ?></small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>



        <?php elseif ($data && isset($data['Response']) && $data['Response'] === "False"): ?>

            <div class="alert alert-danger" role="alert">
                Película no encontrada
            </div>

        <?php endif; ?>





       

        <div class="pelis-recomendadas w-100">
            <h5 class="mb-3">Películas Recomendadas:</h5>
            <div class="row g-4">

                <?php foreach ($recomendadas as $peli): ?>
                    <div class="peli col-md-3 align-items-center text-center width-50px">
                        <img
                            src="<?= $peli['Poster'] ?>"
                            alt="<?= htmlspecialchars($peli['Title']) ?>"
                            class="img-fluid peli-click"
                            data-title="<?= htmlspecialchars($peli['Title']) ?>"
                            data-plot="<?= htmlspecialchars($peli['Plot']) ?>"
                            data-year="<?= $peli['Year'] ?>"
                            data-genre="<?= htmlspecialchars($peli['Genre']) ?>"
                            data-director="<?= htmlspecialchars($peli['Director']) ?>"
                            data-actors="<?= htmlspecialchars($peli['Actors']) ?>"
                            style="cursor:pointer;">
                    </div>
                <?php endforeach; ?>




            </div>
        </div>

        
        <div class="modal fade" id="modalPeli" tabindex="-1" aria-labelledby="modalPeliLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPeliLabel">Título de la Película</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img id="modalPoster" src="" class="img-fluid" alt="Poster">
                            </div>
                            <div class="col-md-8">
                                <p id="modalPlot"></p>
                                <ul>
                                    <li><strong>Año:</strong> <span id="modalYear"></span></li>
                                    <li><strong>Género:</strong> <span id="modalGenre"></span></li>
                                    <li><strong>Director:</strong> <span id="modalDirector"></span></li>
                                    <li><strong>Actores:</strong> <span id="modalActors"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        document.querySelectorAll('.peli-click').forEach(img => {
            img.addEventListener('click', () => {
                // Obtener datos
                const title = img.getAttribute('data-title');
                const plot = img.getAttribute('data-plot');
                const year = img.getAttribute('data-year');
                const genre = img.getAttribute('data-genre');
                const director = img.getAttribute('data-director');
                const actors = img.getAttribute('data-actors');
                const poster = img.getAttribute('src');

                // Setear datos en modal
                document.getElementById('modalPeliLabel').textContent = title;
                document.getElementById('modalPoster').src = poster;
                document.getElementById('modalPlot').textContent = plot;
                document.getElementById('modalYear').textContent = year;
                document.getElementById('modalGenre').textContent = genre;
                document.getElementById('modalDirector').textContent = director;
                document.getElementById('modalActors').textContent = actors;

                // Abrir modal
                var modal = new bootstrap.Modal(document.getElementById('modalPeli'));
                modal.show();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
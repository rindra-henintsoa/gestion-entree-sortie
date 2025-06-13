<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des collaborateurs</title>

    <link rel="icon" type="image/jpg" href="<?= base_url('images/favicon-100x100.jpg') ?>">
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>
    <div class="container">
        <header class="head">
            <div class="logo">
                <a href="<?= route_to('collaborateurs') ?>">
                    <img src="<?= base_url('images/logo-vivetic.svg') ?>" alt="Logo vivetic">
                </a>
            </div>
            <div class="navigation">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="<?= route_to('collaborateurs') ?>">
                            Liste des collaborateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= route_to('logs') ?>">
                            Log des collaborateurs
                        </a>
                    </li>
                </ul>
            </div>
        </header>
        <h2 class="section-title">Logs des collaborateurs par date</h2>

        <form method="get">
            <div class="form-wrapper">
                <div class="form-group field-wrapper">
                    <label for="date">Choisir une date:</label>
                    <input type="date" name="date" id="date" class="form-control" value="<?= esc($date) ?>">
                </div>
                <div class="btn-wrapper">
                    <button type="submit" class="btn btn-secondary submit-btn">Filtrer</button>
                </div>
            </div>
        </form>

        <?php if ($logs) : ?>

        <table class="table table-striped" style="margin-top:20px">
            <tr>
                <th>Nom</th>
                <th>Matricule</th>
                <th>Cartes utilisées</th>
                <th>Première entrée</th>
                <th>Dernière sortie</th>
                <th>Total entrée</th>
                <th>Total sortie</th>
                <th>Volume des pauses (heures)</th>
            </tr> 
        <?php foreach ($logs as $log): 
        ?>
        <tr>
            <td><?= esc($log['Name']); 
                ?></td>
            <td><?= esc($log['Matricule']); 
                ?></td>
            <td><?= esc($log['cartes']); 
                ?></td>
            <td><?= esc($log['heure_premiere_entree']); 
                ?></td>
            <td><?= esc($log['heure_derniere_sortie']); 
                ?></td>
            <td>
                <?= esc($log['total_entrees']);?>
            </td>
            <td>
                <?= esc($log['total_sorties']);?>
            </td>
            <td><?php
                $pin = $log['Matricule'];
                echo isset($volumesPause[$pin]) 
                    ? $volumesPause[$pin]['pause_heure'] 
                    : '0';?>
            </td>
        </tr>
        <?php endforeach; ?>
        </table>
        <div class="pagination">
            <span><?= $total ?> éléments</span>

            <?php if ($currentPage > 1): ?>
                <a href="?date=<?= $date ?>&page=1">&laquo;&laquo;</a>
                <a href="?date=<?= $date ?>&page=<?= $currentPage - 1 ?>">&laquo;</a>
            <?php else: ?>
                <span style="color: #ccc;">&laquo;&laquo;</span>
                <span style="color: #ccc;">&lsaquo;</span>
            <?php endif; ?>

            <span><?= $currentPage ?> sur <?= $totalPages ?></span>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?date=<?= $date ?>&page=<?= $currentPage + 1 ?>">&raquo;</a>
                <a href="?date=<?= $date ?>&page=<?= $totalPages ?>">&raquo;&raquo;</a>
            <?php else: ?>
                <span style="color: #ccc;">&raquo;</span>
                <span style="color: #ccc;">&raquo;&raquo;</span>
            <?php endif; ?>
        </div>
        <?php else :  ?>
            <div class="log-msg">
                <p>Aucun enregistrement trouvé. Veuillez vérifier la date ou les filtres sélectionnés.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
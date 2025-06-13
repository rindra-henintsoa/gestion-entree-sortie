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

        <h2 class="section-title">Liste des collaborateurs</h2>
        <table class="table table-striped" style="max-width:  860px;margin: 0 auto;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Cartes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($collaborateurs as $c): ?>
                    <tr>
                        <td><?= esc($c['name']) ?></td>
                        <td><?= esc($c['pin']) ?></td>
                        <td><?= esc($c['cartes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
        <div class="pagination">
            <span><?= $total ?> éléments</span>
    
            <?php if ($currentPage > 1): ?>
                <a href="?page=1">&laquo;&laquo;</a>
                <a href="?page=<?= $currentPage - 1 ?>">&laquo;</a>
            <?php else: ?>
                <span style="color: #ccc;">&laquo;&laquo;</span>
                <span style="color: #ccc;">&laquo;</span>
            <?php endif; ?>
    
            <span><?= $currentPage ?> sur <?= $totalPages ?></span>
    
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>">&raquo;</a>
                <a href="?page=<?= $totalPages ?>">&raquo;&raquo;</a>
            <?php else: ?>
                <span style="color: #ccc;">&raquo;</span>
                <span style="color: #ccc;">&raquo;&raquo;</span>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
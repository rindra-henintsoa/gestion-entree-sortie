<h2>Liste des collaborateurs</h2>
<table>
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
        <a href="?page=<?= $currentPage - 1 ?>">&lsaquo;</a>
    <?php else: ?>
        <span style="color: #ccc;">&laquo;&laquo;</span>
        <span style="color: #ccc;">&lsaquo;</span>
    <?php endif; ?>

    <span><?= $currentPage ?> sur <?= $totalPages ?></span>

    <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>">&rsaquo;</a>
        <a href="?page=<?= $totalPages ?>">&raquo;&raquo;</a>
    <?php else: ?>
        <span style="color: #ccc;">&rsaquo;</span>
        <span style="color: #ccc;">&raquo;&raquo;</span>
    <?php endif; ?>
</div>

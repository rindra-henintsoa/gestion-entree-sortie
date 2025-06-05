<h2>Logs des collaborateurs par date</h2>

<form method="get">
    <label for="date">Choisir une date:</label>
    <input type="date" name="date" id="date" value="<?= esc($date) ?>">
    <button type="submit">Filtrer</button>
</form>

<?php 
    if ($logs) {

        echo "<pre>";
            var_dump($logs);
        echo "</pre>";
        die;
    }
?>
    
<!-- <table border="1" cellpadding="5" style="margin-top:20px">
    <tr>
        <th>Nom</th>
        <th>Matricule</th>
        <th>Cartes utilisées</th>
        <th>1ère entrée</th>
        <th>Dernière sortie</th>
        <th>Nombre de pauses</th>
        <th>Volume des pauses (heures)</th>
    </tr> -->
    <?php //foreach ($logs as $log): ?>
    <tr>
        <td><?= //esc($log['name']) ?></td>
        <td><?= //esc($log['pin']) ?></td>
        <td><?= //esc($log['cartes']) ?></td>
        <td><?= //esc($log['premiere_entree']) ?></td>
        <td><?= //esc($log['derniere_sortie']) ?></td>
        <td><?= //esc($log['nb_pauses']) ?></td>
        <td><?= //number_format($log['volume_pause'], 2) ?></td>
    </tr>
    <?php //endforeach; ?>
</table>
<?php //endif; ?>

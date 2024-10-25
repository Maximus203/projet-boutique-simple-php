<!DOCTYPE html>
<html lang="fr">
<?php
$connexion = new PDO('mysql:host=localhost;dbname=boutique', 'root', '');
$requete = $connexion->query('CREATE TABLE IF NOT EXISTS article (idArticle INT AUTO_INCREMENT PRIMARY KEY, libelle VARCHAR(255), quantite INT, dateExpiration DATE)');
$requete->execute();

if (isset($_POST['libelle']) && isset($_POST['quantite']) && isset($_POST['dateExpiration'])) {
    $libelle = $_POST['libelle'];
    $quantite = $_POST['quantite'];
    $dateExpiration = $_POST['dateExpiration'];

    $requete = $connexion->prepare('INSERT INTO article(libelle, quantite, dateExpiration) VALUES(:libelle, :quantite, :dateExpiration)');
    $requete->execute([
        'libelle' => $libelle,
        'quantite' => $quantite,
        'dateExpiration' => $dateExpiration
    ]);
}
// Afficher les articles de la base de donnees
$requete = $connexion->query('SELECT * FROM article');
$articles = $requete->fetchAll();

// Modifier article
if (isset($_POST['idArticle']) && isset($_POST['libelle']) && isset($_POST['quantite']) && isset($_POST['dateExpiration'])) {
    $idArticle = $_POST['idArticle'];
    $libelle = $_POST['libelle'];
    $quantite = $_POST['quantite'];
    $dateExpiration = $_POST['dateExpiration'];

    $requete = $connexion->prepare('UPDATE article SET libelle = :libelle, quantite = :quantite, dateExpiration = :dateExpiration WHERE idArticle = :idArticle');
    $requete->execute([
        'idArticle' => $idArticle,
        'libelle' => $libelle,
        'quantite' => $quantite,
        'dateExpiration' => $dateExpiration
    ]);

    header('Location: index.php');
}
// Supprimer article
if (isset($_POST['idArticle'])) {
    $idArticle = $_POST['idArticle'];

    $requete = $connexion->prepare('DELETE FROM article WHERE idArticle = :idArticle');
    $requete->execute([
        'idArticle' => $idArticle
    ]);

    header('Location: index.php');
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
</head>

<body>
    <main style="width: 40%; margin:auto; margin-top: 3em">
        <div class="container m-auto is-6"></div>
        <h1 class="title is-1">Boutique 🛍️</h1>
        <?php if (count($articles)) : ?>
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Quantité</th>
                        <th>Date d'expiration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) : ?>
                        <tr>
                            <td><?= $article['libelle'] ?></td>
                            <td><?= $article['quantite'] ?></td>
                            <td><?= $article['dateExpiration'] ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="idArticle" value="<?= $article['idArticle'] ?>">
                                    <button class="button is-warning" onclick="afficherModal(<?= $article['idArticle'] ?>); return false;" type="submit">Modifier</button>
                                </form>
                                <form action="" method="post">
                                    <input type="hidden" name="idArticle" value="<?= $article['idArticle'] ?>">
                                    <button class="button is-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="modal" id="modal<?= $article['idArticle'] ?>">
                <div class="modal-background"></div>
                <div class="modal-content">
                    <div class="box">
                        <form action="" method="post">
                            <input type="hidden" name="idArticle" value="<?= $article['idArticle'] ?>">
                            <div class="field">
                                <label class="label">Libellé</label>
                                <div class="control">
                                    <input class="input" type="text" name="libelle" value="<?= $article['libelle'] ?>" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Quantité</label>
                                <div class="control">
                                    <input class="input" type="number" name="quantite" value="<?= $article['quantite'] ?>" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Date d'expiration</label>
                                <div class="control">
                                    <input class="input" type="date" name="dateExpiration" value="<?= $article['dateExpiration'] ?>" required>
                                </div>
                            </div>
                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-link" type="submit">Modifier</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <button class="modal-close is-large" aria-label="close"></button>
            </div>
            <script>
                function afficherModal(idArticle) {
                    document.getElementById('modal' + idArticle).classList.add('is-active');
                    return false;
                }
                document.querySelectorAll('.modal-close').forEach(function(button) {
                    button.addEventListener('click', function() {
                        document.getElementById('modal<?= $article['idArticle'] ?>').classList.remove('is-active');
                    });
                });
            </script>
        <?php else : ?>
            <p class="notification is-warning">Pas d'article</p>
        <?php endif; ?>
        <form action="" method="post">
            <div class="field">
                <label class="label">Libellé</label>
                <div class="control">
                    <input class="input" type="text" name="libelle" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Quantité</label>
                <div class="control">
                    <input class="input" type="number" name="quantite" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Date d'expiration</label>
                <div class="control">
                    <input class="input" type="date" name="dateExpiration" required>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link" type="submit">Ajouter</button>
                </div>
            </div>
        </form>
        </div>
    </main>
</body>

</html>
<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

class SQL_Offer
{
    private $dbh;

    public function __construct($login, $password, $database_name, $host = 'localhost')
    {
        try {
            $this->dbh = new PDO("mysql:dbname=$database_name;host=$host", $login, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
    }

    /**
     * @param $query
     * @param bool|array $params
     * @return PDOStatement
     */
    public function query($query, $params = false)
    {
        if ($params) {
            $req = $this->dbh->prepare($query);
            $req->execute($params);
        } else {
            $req = $this->dbh->query($query);
        }
        return $req;
    }

    public function view_offer()
    {
        $v = $this->query('SELECT * FROM users LEFT JOIN offers ON users.id_user = offers.id_user WHERE `id_offer` = :id',
            [':id' => $_GET['id']])->fetchAll();
        return $v;
    }

    public function list_offer()
    {
        $i = $this->query('SELECT * FROM users LEFT JOIN offers ON users.id_user = offers.id_user ORDER BY users.id_user DESC')->fetchAll();
        foreach ($i as $inf) {
            echo '<tr>
                <td><img src="' . $inf['img_offer'] . '" height="200px" width="200px" alt=""></td>
                <td>' . $inf['city'] . '</td>
                <td>' . $inf['lastname'] . ' ' . $inf['firstname'] . '</td>
                <td><h4>' . $inf['name_offer'] . '</h4><br><p>' . $inf['description'] . '</p></td>
                <td> <button type="button" class="btn btn-primary">' . $inf['price'] . '</button> €</td>
                <td><button type="button" class="btn btn-primary"><a href="viewoffres.php?id="' . $inf['id_offer'] . '></a></button></td>
                </tr>';
        }
    }

    public function create_offer()
    {
        if (isset($_FILES['img'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // Vérifie le type MIME du fichier
            $mime = finfo_file($finfo, $_FILES['img']['tmp_name']); // Regarde dans ce fichier le type MIME
            finfo_close($finfo); // Fermeture de la lecture
            $filename = explode('.', $_FILES['img']['name']); // Explosion du nom sur le point
            $extension = $filename[count($filename) - 1]; // L'extension du fichier
            $extension_valide = ['png', 'jpeg', 'gif', 'jpg'];
            $mime_valide = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];
            if ((in_array($extension, $extension_valide) && in_array($mime, $mime_valide))) {
                if ($_FILES['img']['size'] < 20971520) {
                    $dossier = 'users/user_' . $_SESSION['id_user'];
                    if (!is_dir($dossier)) {
                        mkdir($dossier);
                    }
                    move_uploaded_file($_FILES['img']['tmp_name'],
                        'users/user_' . $_SESSION['id_user'] . '/' . $_FILES['img']['name']);
                }
            }
            $img = $_FILES['img']['name'];
        }
        else {
            $img = NULL;
        }
        if (!empty($_POST)) {
            $this->query('INSERT INTO offers VALUES (NULL, :name, :city, :place, :dispo, :desc, NOW(), NOW(), :price, :img, :id_u)',
                [':name' => $_POST['title'], ':city' => $_POST['city'], ':place' => $_POST['adr'], ':dispo' => $_POST['dsp'],
                    ':desc' => $_POST['desc'], ':price' => $_POST['price'], ':img' => $img, ':id_u' => $_SESSION['id_user']]);
            header('Location: index.php');
        }
    }

    public function upload()
    {
        if (!$_SESSION['connected']) {
            header('Location: connexion.php');
        } else {
            if (isset($_FILES['file'])) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE); // Vérifie le type MIME du fichier
                $mime = finfo_file($finfo, $_FILES['file']['tmp_name']); // Regarde dans ce fichier le type MIME
                finfo_close($finfo); // Fermeture de la lecture
                $filename = explode('.', $_FILES['file']['name']); // Explosion du nom sur le point
                $extension = $filename[count($filename) - 1]; // L'extension du fichier
                $extension_valide = ['png', 'jpeg', 'gif', 'jpg'];
                $mime_valide = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];
                if ((in_array($extension, $extension_valide) && in_array($mime, $mime_valide))) {
                    if ($_FILES['file']['size'] < 20971520) {
                        $dossier = 'upload/' . $_SESSION['id_user'];
                        if (!is_dir($dossier)) {
                            mkdir($dossier);
                        }
                        move_uploaded_file($_FILES['file']['tmp_name'],
                            'upload/' . $_SESSION['id_user'] . '/' . $_FILES['file']['name']);
                        $this->query('INSERT INTO `image`(`name_image`, `date`, `ip_address`, `id_user`) VALUES (:name, NOW(), :ip, :id)',
                            [':name' => $_FILES['file']['name'], ':ip' => $_SERVER['REMOTE_ADDR'], ':id' => $_SESSION['id_user']]);
                        echo '<div class="alert success"><strong>Upload réussi !</strong> Votre image a bien été envoyé.</div>';
                    } else {
                        echo '<div class="alert"><strong>Echec ! </strong>Fichier trop volumineux !</div>';
                    }

                } else {
                    echo '<div class="alert"><strong>Echec ! </strong>Format incorrect !</div>';
                }
            }
        }
    }

}

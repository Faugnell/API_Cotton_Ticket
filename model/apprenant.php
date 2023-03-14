<?php

class Apprenant
{
    // Connexion
    private $_connexion;
    private $_table = "utilisateur";

    // variables publiques
    public $_id_utilisateur;
    public $_mail;
    public $_pass;
    public $_nom;
    public $_prenom;
    public $_statut = "Apprenant";
    public $_admin = 0;

    /**
     * Constructeur avec $db pour la connexion à la base de données
     * 
     * @param PDO $db
     */
    public function __construct($db)
    {
        $this->_connexion = $db;
    }

    /**
     * Tester si le mail et le password correspondent pour une connexion
     * 
     * @param string $mail Mail utilisateur
     * @param string $password Mot de passe
     * @return int Renvoie l'identifiant de la personne so le mail et le mot de passe sont bon
     */
    public function connexion($mail, $password)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select pass from utilisateur where mail = ?');

        // Vérification de l'email et du mdp qui renvoie true
        $rq->execute([$mail]);
        if (password_verify($password, $rq->fetchColumn())) {
            $rq = $this->_connexion->prepare('select id_utilisateur from utilisateur where mail = ?');
            $rq->execute([$mail]);
            return $rq->fetchColumn();
        } else {
            return false;
        }
    }

    /**
     * Ajoute / Créer un apprenant
     * 
     * @return int id_utilisateur ID de l'utilisateur qui vient d'être ajouter en BDD ou false si l'ajout n'a pas abouti
     */

    public function ajouter_apprenant()
    {
        //On vérifie que l'utilisateur n'est pas déjà existant dans la BDD avec ce mail
        $rq = $this->_connexion->prepare('SELECT COUNT(*) FROM utilisateur WHERE mail = ?');
        $rq->execute([$this->_mail]);
        if ($rq->fetchColumn() == 0)
        {
            // Ecriture de la requête SQL en y insérant le nom de la table
            $sql = "INSERT INTO {$this->_table} SET mail=:mail, pass=:pass, nom=:nom, prenom=:prenom, statut=:statut, admin=:admin";
            // Préparation de la requête
            $rq = $this->_connexion->prepare($sql);
            // Ajout des données protégées
            $rq->bindParam(":mail", $this->_mail, PDO::PARAM_STR);
            $rq->bindValue(":pass", password_hash($this->_pass, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $rq->bindParam(":nom", $this->_nom, PDO::PARAM_STR);
            $rq->bindParam(":prenom", $this->_prenom, PDO::PARAM_STR);
            $rq->bindParam(":statut", $this->_statut, PDO::PARAM_STR);
            $rq->bindParam(":admin", $this->_admin, PDO::PARAM_BOOL);
            // Exécution de la requête
            if ($rq->execute()){
                return $this->_connexion->lastInsertId();
            }
        }
        return false;
    }
}
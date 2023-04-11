<?php

class Utilisateur
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
    public $_statut;
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
     * Vérifie que le compte existe
     * 
     * 
     */
    public function existe_utilisateur($mail)
    {
        $rq = $this->_connexion->prepare('select * from utilisateur where mail = ?');

        // Vérification de l'id ticket pédagogique qui renvoie true
        $rq->execute([$mail]);
        if ($rq->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie le mot de pass
     */

    public function verification_pass($mail, $password)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select pass from utilisateur where mail = ?');

        // Vérification de l'email et du mdp qui renvoie true
        $rq->execute([$mail]);
        if (password_verify($password, $rq->fetchColumn())) {
            return true;
        } else {
            return false;
        }
    }
}

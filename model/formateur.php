<?php

class Formateur
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
    public $_statut = "Formateur";
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
     * Tester so le mail et le password correspondent pour une connexion
     * 
     * @param string $mail Mail utilisateur
     * @param string $password Mot de passe
     * @return int Renvoie l'identifiatn de la personne so le mail et le mot de passe sont bon
     */
    public function connexion($mail, $password)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select pass from utilisateur where mail = ?');

        // Vérification de l'email et du mdp qui renvoie true
        $rq->execute([$mail]);
        if (password_verify($password, $rq->fetchColumn()))
        {
            $rq = $this->_connexion->prepare('select id_utilisateur from utilisateur where mail = ?');
            $rq->execute([$mail]);
            return $rq->fetchColumn();
        }
        else
        {
            return false;
        }
    }
}
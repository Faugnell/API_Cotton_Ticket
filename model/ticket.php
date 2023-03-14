<?php 

class Ticket
{
    // Connexion
    private $_connexion;
    private $_table = "ticket";

    // variables publiques
    public $_id_ticket;
    public $_id_ticket_p;
    public $_id_ticket_t;
    public $date_ouverture;
    public $resolution;
    public $clos;
    public $date_clos;

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
     * lire tout les tickets de l'utilisateur
     * 
     * @param int $id_utilisateur 
     */
    public function lire_ticket($id_utilisateur)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select * from ticket where id_utilisateur = ?');

        // Vérification de l'id ticket pédagogique qui renvoie true
        $rq->execute([$id_utilisateur]);
        if ($rq->fetchColumn()) {
            $rq = $this->_connexion->prepare('select * from view_ticket_p where id_utilisateur = ?');
            $rq->execute([$id_utilisateur]);
            return $rq->fetchColumn();
        } else {
            return false;
        }
    }
}
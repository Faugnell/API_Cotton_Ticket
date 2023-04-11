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
        $sql = "select * from view_ticket where id_utilisateur = {$id_utilisateur}";
        $rq = $this->_connexion->prepare($sql);
        $rq->execute([$id_utilisateur]);
        while ($donnees = $rq->fetchAll(PDO::FETCH_ASSOC)) {
            return $donnees;
        }
    }
}

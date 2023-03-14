<?php

class TicketPedagogique
{
    // Connexion
    private $_connexion;
    private $_table = "ticket_pedagogique";

    // variables publiques
    public $_id_ticket;
    public $_id_ticket_p;
    public $_date_ouverture;
    public $_resolution;
    public $_clos;
    public $_date_clos;
    public $_id_utilisateur;
    public $_id_question_p;
    public $_nom_question;
    public $_reponse;

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
     * Créer un ticket
     * 
     * @return int id_ticket ID du ticket qui vient d'être ajouter en BDD ou false si l'ajout n'a pas abouti
     */

    public function ajouter_ticket_pedagogique()
    {
        // On vérifie que le ticket n'est pas déjà existant dans la BDD avec l'id_ticket
        $rq = $this->_connexion->prepare('SELECT COUNT(*) FROM ticket_pedagogique WHERE id_ticket_p = ?');
        $rq->execute([$this->_id_ticket_p]);
        if ($rq->fetchColumn() == 0) {
            // Ecriture de la requête SQL en y insérant le nom de la table
            $sql = "INSERT INTO {$this->_table} SET id_ticket=:id_ticket, id_ticket_p=:id_ticket_p, date_ouverture=:date_ouverture, resolution=:resolution, clos=:clos, date_clos=:date_clos";
            // Préparation de la requête
            $rq = $this->_connexion->prepare($sql);
            // Ajout des données protégées
            $rq->bindParam(":mail", $this->_id_ticket, PDO::PARAM_STR);
            $rq->bindParam(":pass", $this->_id_ticket_p, PDO::PARAM_STR);
            $rq->bindParam(":nom", $this->_date_ouverture, PDO::PARAM_STR);
            $rq->bindParam(":prenom", $this->_resolution, PDO::PARAM_STR);
            $rq->bindParam(":statut", $this->_clos, PDO::PARAM_STR);
            $rq->bindParam(":admin", $this->_date_clos, PDO::PARAM_BOOL);
            // Exécution de la requête
            if ($rq->execute()) {
                return $this->_connexion->lastInsertId();
            }
        }
        return false;
    }

    /**
     * Tester l'id du ticket pédagogique pour le lire
     * 
     * @param int $id_ticket_p id du ticket pédagogique
     */
    public function lire_ticket($id_ticket_p)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select id_ticket_p from ticket where id_ticket_p = ?');

        // Vérification de l'id ticket pédagogique qui renvoie true
        $rq->execute([$id_ticket_p]);
        if ($rq->fetchColumn()) {
            $rq = $this->_connexion->prepare('select * from view_ticket_p where id_ticket_p = ?');
            $rq->execute([$id_ticket_p]);
            return $rq->fetchColumn();
        } else {
            return false;
        }
    }
}

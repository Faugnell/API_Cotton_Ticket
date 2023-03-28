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
            $sql = "INSERT INTO {$this->_table} SET fk_id_utilisateur=:id_utilisateur";
            // Préparation de la requête
            $rq = $this->_connexion->prepare($sql);
            // Ajout des données protégées
            $rq->bindParam(":id_utilisateur", $this->_id_utilisateur, PDO::PARAM_STR);
            // Exécution de la requête
            if ($rq->execute()) {
                return $this->_connexion->lastInsertId();
            }
            $sql = "INSERT INTO reponse pedagogique set fk_id_ticket=:id_ticket, fk_id_question_p=:id_question_p, reponse=:reponse";
            $rq = $this->_connexion->prepare($sql);
            $rq->bindParam(":id_ticket", $this->_id_ticket, PDO::PARAM_STR);
            $rq->bindParam(":id_question_p", $this->_id_question_p, PDO::PARAM_STR);
            $rq->bindParam(":reponse", $this->_reponse, PDO::PARAM_STR);
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

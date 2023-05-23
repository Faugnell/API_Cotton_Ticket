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
    public $_rep1 = "";
    public $_rep2 = "";
    public $_rep3 = "";
    public $_rep4 = "";
    public $_rep5 = "";
    public $_rep6 = "";
    public $_rep7 = "";

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
        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO {$this->_table} SET fk_id_utilisateur=:id_utilisateur";
        // Préparation de la requête
        $rq = $this->_connexion->prepare($sql);
        // Ajout des données protégées
        $rq->bindParam(":id_utilisateur", $this->_id_utilisateur, PDO::PARAM_INT);
        // Exécution de la requête
        if ($rq->execute()) {
            $this->_id_ticket_p = $this->_connexion->lastInsertId();
        }
        $sql = "INSERT INTO reponse_pedagogique
        (fk_id_ticket_p, fk_id_question_p, reponse) values
        (:id_ticket_p, 1, :rep1),
        (:id_ticket_p, 2, :rep2),
        (:id_ticket_p, 3, :rep3),
        (:id_ticket_p, 4, :rep4),
        (:id_ticket_p, 5, :rep5),
        (:id_ticket_p, 6, :rep6),
        (:id_ticket_p, 7, :rep7)";
        $rq = $this->_connexion->prepare($sql);
        $rq->bindParam(":id_ticket_p", $this->_id_ticket_p, PDO::PARAM_INT);
        $rq->bindParam(":rep1", $this->_rep1, PDO::PARAM_STR);
        $rq->bindParam(":rep2", $this->_rep2, PDO::PARAM_STR);
        $rq->bindParam(":rep3", $this->_rep3, PDO::PARAM_STR);
        $rq->bindParam(":rep4", $this->_rep4, PDO::PARAM_STR);
        $rq->bindParam(":rep5", $this->_rep5, PDO::PARAM_STR);
        $rq->bindParam(":rep6", $this->_rep6, PDO::PARAM_STR);
        $rq->bindParam(":rep7", $this->_rep7, PDO::PARAM_STR);
        if ($rq->execute()) {
            return $this->_id_ticket_p;
        }
        return false;
    }

    /**
     * Tester l'id du ticket pédagogique pour le lire
     * 
     * @param int $id_ticket_p id du ticket pédagogique
     */
    public function lire_ticket_pedagogique($id_ticket_p)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select id_ticket_p from ticket where id_ticket_p = ?');

        // Vérification de l'id ticket pédagogique qui renvoie true
        $rq->execute([$id_ticket_p]);
        if ($rq->fetchColumn()) {
            $rq = $this->_connexion->prepare('select * from view_ticket_p where id_ticket_p = ?');
            $rq->execute([$id_ticket_p]);
            while ($donnees = $rq->fetchAll(PDO::FETCH_ASSOC)) {
                return $donnees;
            }
        } else {
            return false;
        }
    }

    /**
     * Afficher la liste des tickets dépendant de si on à un utilisateur ou non
     * 
     * @param int $id_utilisateur
     */
    public function liste_ticket_pedagogique($id_utilisateur = "")
    {
        $sql = "select * from view_ticket_p";
        if ($id_utilisateur != "") {
            $sql .= " where id_utilisateur = {$id_utilisateur}";
        }
        $rq = $this->_connexion->prepare($sql);
        $rq->execute();
        while ($donnees = $rq->fetchAll(PDO::FETCH_ASSOC)) {
            return $donnees;
        }
    }
}

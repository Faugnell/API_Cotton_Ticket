<?php

class TicketTechnique
{
    // Connexion
    private $_connexion;
    private $_table = "ticket_technique";

    // variables publiques
    public $_id_ticket;
    public $_id_ticket_t;
    public $_id_type;
    public $_commentaire;
    public $_date_ouverture;
    public $_resolution;
    public $_clos;
    public $_date_clos;
    public $_id_utilisateur;

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

    public function ajouter_ticket_technique()
    {
        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO {$this->_table} SET id_utilisateur=:id_utilisateur, id_type_t=:id_type, commentaire=:commentaire";
        // Préparation de la requête
        $rq = $this->_connexion->prepare($sql);
        // Ajout des données protégées
        $rq->bindParam(":id_utilisateur", $this->_id_utilisateur, PDO::PARAM_INT);
        $rq->bindParam(":id_type", $this->_id_type, PDO::PARAM_INT);
        $rq->bindParam(":commentaire", $this->_commentaire, PDO::PARAM_STR);
        // Exécution de la requête
        if ($rq->execute()) {
            return $this->_connexion->lastInsertId();;
        }
        return false;
    }

    /**
     * Tester l'id du ticket pédagogique pour le lire
     * 
     * @param int $id_ticket_p id du ticket pédagogique
     */
    public function lire_ticket_technique($id_ticket_t)
    {
        // Récupération des infos BDD
        $rq = $this->_connexion->prepare('select id_ticket_t from ticket where id_ticket_t = ?');

        // Vérification de l'id ticket pédagogique qui renvoie true
        $rq->execute([$id_ticket_t]);
        if ($rq->fetchColumn()) {
            $rq = $this->_connexion->prepare('select * from view_ticket_t where id_ticket_t = ?');
            $rq->execute([$id_ticket_t]);
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
    public function liste_ticket_technique($id_utilisateur = "")
    {
        $sql = "select * from view_ticket_t";
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

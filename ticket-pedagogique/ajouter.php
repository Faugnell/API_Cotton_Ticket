<?php
// Headers requis
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: GET");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    include_once('../model/bdd.php');
    include_once('../model/ticketPedagogique.php');

    // On récupère notre BDD
    $db = Bdd::getInstance()->getConnection();

    // On instancie le ticket pedagogique
    $ticketPedagogique = new TicketPedagogique($db);

    // On récupère les données
    //$donnees = json_decode(file_get_contents("php://input"));
    if (!empty($_GET['id_utilisateur'])) {
        // Vérification que les données ne sont pas vide avant de les remplir
        if(!empty($_PUT['rep1'])){
            $ticketPedagogique->_rep1 = $_PUT['rep1'];
        }
        if(!empty($$_PUT['rep2'])){
            $ticketPedagogique->_rep2 = $_PUT['rep2'];
        }
        if(!empty($$_PUT['rep3'])){
            $ticketPedagogique->_rep3 = $_PUT['rep3'];
        }
        if(!empty($$_PUT['rep4'])){
            $ticketPedagogique->_rep4 = $_PUT['rep4'];
        }
        if(!empty($$_PUT['rep5'])){
            $ticketPedagogique->_rep5 = $_PUT['rep5'];
        }
        if(!empty($$_PUT['rep6'])){
            $ticketPedagogique->_rep6 = $_PUT['rep6'];
        }
        if(!empty($$_PUT['rep7'])){
            $ticketPedagogique->_rep7 = $_PUT['rep7'];
        }
        // Ici on a reçu les données
        // On hydrate notre objet
        $ticketPedagogique->_id_utilisateur = $_PUT['id_utilisateur'];
        $id_ticket_p = $ticketPedagogique->ajouter_ticket_pedagogique();
        var_dump($id_ticket_p);
        if ($id_ticket_p) {
            // Ici la création a fonctionné
            // On envoie un code 201
            http_response_code(201);
            echo json_encode(["message" => "Le ticket à bient été créer", "id_ticket_p" => $id_ticket_p], JSON_UNESCAPED_UNICODE);
        } else {
            // Ici la création n'a pas fonctionnée
            // On envoie un code 503
            http_response_code(503);
            echo json_encode(["message" => "Le ticket est déjà existant"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Les informations de création sont incomplète"), JSON_UNESCAPED_UNICODE);
    }
} else {
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(array("message" => "La méthode n'est pas autorisée"), JSON_UNESCAPED_UNICODE);
}

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

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    include_once('../model/bdd.php');
    include_once('../model/ticketPedagogique.php');

    // On recupère notre BDD
    $db = Bdd::getInstance()->getConnection();

    // On instancie le formateur
    $ticketPedagogique = new TicketPedagogique($db);

    // On récupère les données
    //$donnees = json_decode((file_get_contents("php://input")));

    // On vérifie qu'on à bien un id utilisateur
    if (!empty($_GET['id_utilisateur']))
    {
        $tickets_p = $ticketPedagogique->liste_ticket_pedagogique($_GET['id_utilisateur']);
        if ($tickets_p)
        {
            echo json_encode($tickets_p);
        }
    }
    else 
    {
        // 404 Not Found
        http_response_code(404);
        echo json_encode(array("message" => "Il n'y a pas de ticket"));
    }
}
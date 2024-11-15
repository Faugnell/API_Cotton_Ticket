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
    include_once('../model/ticketTechnique.php');

    // On recupère notre BDD
    $db = Bdd::getInstance()->getConnection();

    // On instancie le formateur
    $ticketTechnique = new TicketTechnique($db);

    // On récupère les données
    //$donnees = json_decode((file_get_contents("php://input")));

    // On vérifie qu'on à bien un ticket pedagogique
    if (!empty($_GET['id_ticket_t']))
    {
        $ticket_t = $ticketTechnique->lire_ticket_technique($_GET['id_ticket_t']);
        if ($ticket_t)
        {
            echo json_encode($ticket_t);
        }
    }
    else 
    {
        // 404 Not Found
        http_response_code(404);
        echo json_encode(array("message" => "Il n'y a pas de mail"));
    }
}
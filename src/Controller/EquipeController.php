<?php

declare(strict_types=1); 

namespace App\Controller;

use App\Model\Equipe;


class EquipeController extends Controller
{

    /**
     * Définit les en-têtes CORS pour autoriser les requêtes provenant d'autres domaines.
     */

    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    public function handleOptions()
    {
        $this->setCorsHeaders();
        exit;
    }
    /**
     * Récupère et renvoie toutes les équipes en JSON.
     */

    public function index()
    {
        header('Content-Type: application/json');
        $this->setCorsHeaders();

        $equipes = Equipe::getInstance()->findAll();
        echo json_encode($equipes);
    }
    /**
     * Redirige vers une page de documentation.
     */

    public function welcome()
    {
        header('Location: ./doc.html');
        exit;  
    }
    /**
     * Crée une nouvelle équipe avec les données JSON reçues.
     */
    public function create()
    {
        $this->setCorsHeaders(); 
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true); 

        if (!isset($data['nom']) || !isset($data['niveau'])) {
            var_dump($data);
            return;
        }
         // Crée une nouvelle équipe avec les données fournies et initialise certaines valeurs par défaut.
        $createdEquipe = Equipe::getInstance()->create([
            'nom' => $data['nom'],
            'matches_joues' => 0,
            'victoires' => 0,
            'defaites' => 0,
            'matches_nuls' => 0,
            'buts_marques' => 0,
            'buts_encaisses' => 0,
            'niveau' => $data['niveau']
        ]);

        if ($createdEquipe) {
            echo json_encode(['message' => 'Equipe created successfully', 'equipe' => $createdEquipe]);
        } else {
            echo json_encode(['message' => 'Failed to create equipe']);
        }
    }
    /**
     * Récupère une équipe par son ID et la renvoie en JSON.
     *
     * @param int|string $id L'identifiant de l'équipe
     */

    public function findequipe(int|string $id)
    {
        $this->setCorsHeaders(); 
        header('Content-Type: application/json');

        $id = (int) $id;
        $equipe = Equipe::getInstance()->find($id);
        if ($equipe) {
            echo json_encode($equipe);
        } else {
            echo json_encode(['message' => 'Equipe not found']);
        }
    }
    /**
     * Met à jour une équipe avec les données JSON reçues.
     *
     * @param int|string $id L'identifiant de l'équipe
     */


    public function update(int|string $id)
    {
        $this->setCorsHeaders(); 

        $data = json_decode(file_get_contents('php://input'), true); 

        $id = (int) $id;

        if (empty($data)) {
            echo json_encode(['message' => 'No data provided for update']);
            return;
        }
        // Ajoute les champs présents dans les données reçues au tableau de mise à jour.
        $updateData = [];
        if (isset($data['nom'])) {
            $updateData['nom'] = $data['nom'];
        }
        if (isset($data['matches_joues'])) {
            $updateData['matches_joues'] = $data['matches_joues'];
        }
        if (isset($data['victoires'])) {
            $updateData['victoires'] = $data['victoires'];
        }
        if (isset($data['defaites'])) {
            $updateData['defaites'] = $data['defaites'];
        }
        if (isset($data['matches_nuls'])) {
            $updateData['matches_nuls'] = $data['matches_nuls'];
        }
        if (isset($data['buts_marques'])) {
            $updateData['buts_marques'] = $data['buts_marques'];
        }
        if (isset($data['buts_encaisses'])) {
            $updateData['buts_encaisses'] = $data['buts_encaisses'];
        }
        if (isset($data['niveau'])) {
            $updateData['niveau'] = $data['niveau'];
        }
        // Met à jour l'équipe et vérifie si l'opération a réussi, renvoyant un message approprié.
        $updateEquipe = Equipe::getInstance()->update($id, $updateData);
        if ($updateEquipe) {
            echo json_encode(['message' => 'Equipe updated successfully', 'equipe' => $updateEquipe]);
        } else {
            echo json_encode(['message' => 'Failed to update equipe']);
        }
    }
    /**
     * Supprime une équipe selon son ID.
     *
     * @param int|string $id L'identifiant de l'équipe
     */

    public function delete(int|string $id)
    {
        $this->setCorsHeaders(); 

        $id = (int) $id;
        $delete = Equipe::getInstance()->delete($id);
        // Vérifie si la suppression a réussi et renvoie un message approprié.
        if ($delete) {
            echo json_encode(['message' => 'Equipe deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete equipe']);
        }
    }
}

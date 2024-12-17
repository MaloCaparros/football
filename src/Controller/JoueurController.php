<?php

declare(strict_types=1); 

namespace App\Controller;

use App\Model\Joueurs;

class JoueurController extends Controller
{

    private function setCorsHeaders()
    {
        /**
            * Définit les en-têtes CORS pour permettre les requêtes cross-origin.
        */
        header('Access-Control-Allow-Origin: *'); // Autorise toutes les origines à accéder à l'API.
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS'); // Spécifie les méthodes HTTP autorisées.
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); // Définit les en-têtes acceptés.
    }

    public function handleOptions()
    {
        $this->setCorsHeaders();
        http_response_code(204);
        exit;
    }

     /**
     * Renvoie tous les joueurs sous forme de JSON.
     */

    public function index()
    {
        header('Content-Type: application/json');
        $this->setCorsHeaders();
        $joueurs = Joueurs::getInstance()->findAll();
        echo json_encode($joueurs);
    }

    /**
     * Renvoie tous les joueurs d'une équipe spécifique.
     *
     * @param int|string $id ID de l'équipe
     */

    public function joueurTeam(
        int|string $id
    ) {
        header('Content-Type: application/json');
        $this->setCorsHeaders();
        $id = (int) $id;

        $joueurs = Joueurs::getInstance()->findAllBy(['equipe_id' => $id]);
        echo json_encode($joueurs);
    }

    /**
     * Renvoie un joueur spécifique selon son ID.
     *
     * @param int|string $id ID du joueur
     */

    public function findJoueur(int|string $id)
    {
        $this->setCorsHeaders(); 
        header('Content-Type: application/json');

        $id = (int) $id;
        $equipe = Joueurs::getInstance()->find($id);
        if ($equipe) {
            echo json_encode($equipe);
        } else {
            echo json_encode(['message' => 'Equipe not found']);
        }
    }

    /**
     * Crée un nouveau joueur avec les données fournies dans la requête JSON.
     */

    public function create()
    {
        $this->setCorsHeaders();

        $data = json_decode(file_get_contents('php://input'), true);

        // Vérifie si les données obligatoires sont présentes.

        if (isset($data['nom'], $data['equipe_id'], $data['position'])) {
            $createdJoueur = Joueurs::getInstance()->create([
                'nom' => $data['nom'],
                'equipe_id' => $data['equipe_id'],
                'position' => $data['position'],
                'buts' => 0,
                'passes_decisives' => 0,
            ]);
            // Vérifie si la création a réussi et renvoie un message approprié.
            if ($createdJoueur) {
                echo json_encode(['message' => 'Joueur created successfully', 'joueur' => $createdJoueur]);
            } else {
                echo json_encode(['message' => 'Failed to create joueurs']);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    /**
     * Met à jour un joueur avec les données fournies dans la requête JSON.
     *
     * @param int|string $id ID du joueur
     */

    public function update(
        int|string $id
    ) {
        header('Content-Type: application/json');

        $this->setCorsHeaders();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int) $id;

        // Ajoute les champs à mettre à jour seulement s'ils sont définis dans la requête.

        $updateJoueur = [];
        if (isset($data['nom'])) {
            $updateJoueur['nom'] = $data['nom'];
        }
        if (isset($data['equipe_id'])) {
            $updateJoueur['equipe_id'] = $data['equipe_id'];
        }
        if (isset($data['position'])) {
            $updateJoueur['position'] = $data['position'];
        }
        if (isset($data['buts'])) {
            $updateJoueur['buts'] = $data['buts'];
        }
        if (isset($data['passes_decisives'])) {
            $updateJoueur['passes_decisives'] = $data['passes_decisives'];
        }

        $update = Joueurs::getInstance()->update($id, $updateJoueur); // Met à jour le joueur.
        if ($update) {
            echo json_encode(['message' => 'Equipe updated successfully', 'joueur' => $update]);
        } else {
            echo json_encode(['message' => 'Failed to update equipe']);
        }
    }

    /**
     * Supprime un joueur selon son ID.
     *
     * @param int|string $id ID du joueur
     */

    public function delete(
        int|string $id
    ) {
        header('Content-Type: application/json');
        $this->setCorsHeaders();

        $id = (int) $id;
        $delete = Joueurs::getInstance()->delete($id);

        if ($delete) {
            echo json_encode(['message' => 'Joueur deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete Joueur']);
        }
    }
}

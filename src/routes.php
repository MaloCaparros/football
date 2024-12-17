<?php

declare(strict_types=1);
/*
-------------------------------------------------------------------------------
Les routes
-------------------------------------------------------------------------------
 */

return [
    // Routes pour la gestion des équipes
    ['GET', '/', 'equipe@welcome' ],
    ['GET', '/api/v1/equipe', 'equipe@index'],
    ['GET', '/api/v1/equipe/{id:\d+}', 'equipe@findequipe'],            
    ['POST', '/api/v1/equipe', 'equipe@create'],            
    ['PUT', '/api/v1/equipe/{id:\d+}', 'equipe@update'],  
    ['DELETE', '/api/v1/equipe/{id:\d+}', 'equipe@delete'],  

    // Routes pour la gestion des joueurs
    ['GET', '/api/v1/joueur', 'joueur@index'],
    ['GET', '/api/v1/joueur/{id:\d+}', 'joueur@joueurTeam'],
    ['GET', '/api/v1/joueursolo/{id:\d+}', 'joueur@findJoueur'],          
    ['POST', '/api/v1/joueur', 'joueur@create'],             
    ['PATCH', '/api/v1/joueur/{id:\d+}', 'joueur@update'],   
    ['DELETE', '/api/v1/joueur/{id:\d+}', 'joueur@delete'],


    // Routes pour gérer les pré-requêtes CORS
    ['OPTIONS', '/api/v1/equipe', 'equipe@handleOptions'],     
    ['OPTIONS', '/api/v1/equipe/{id:\d+}', 'equipe@handleOptions'], 
    ['OPTIONS', '/api/v1/joueur', 'joueur@handleOptions'],     
    ['OPTIONS', '/api/v1/joueur/{id:\d+}', 'joueur@handleOptions'], 
];

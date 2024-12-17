-- Création de la table Equipe
CREATE TABLE Equipe (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    matches_joues INTEGER DEFAULT 0,
    victoires INTEGER DEFAULT 0,
    defaites INTEGER DEFAULT 0,
    matches_nuls INTEGER DEFAULT 0,
    buts_marques INTEGER DEFAULT 0,
    buts_encaisses INTEGER DEFAULT 0,
    niveau INTEGER DEFAULT 0
);

-- Création de la table Joueurs
CREATE TABLE Joueurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    equipe_id INTEGER NOT NULL, 
    position TEXT, 
    buts INTEGER DEFAULT 0,
    passes_decisives INTEGER DEFAULT 0,
    FOREIGN KEY (equipe_id) REFERENCES Equipe(id) ON DELETE CASCADE
);


INSERT INTO Joueurs (nom, equipe_id, position, buts, passes_decisives) VALUES
    -- Les Lions (equipe_id = 1)
    ('Alex Dupont', 1, 'Attaquant', 8, 5),
    ('Maxime Durand',1, 'Défenseur', 1, 2),
    ('Thomas Bernard', 1, 'Milieu', 5, 7),
    ('Hugo Petit', 1, 'Gardien', 0, 0);
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder principal — TechMada RH
 * Lance : php spark db:seed MainSeeder
 */

class MainSeeder extends Seeder
{
    public function run(): void
    {
        $db    = \Config\Database::connect();
        $annee = (int) date('Y');

        // ─── DÉSACTIVER LES FOREIGN KEYS (SQLite) ─────────────────
        $db->simpleQuery('PRAGMA foreign_keys = OFF');

        // ─── VIDER LES TABLES (ordre inverse des dépendances) ─────
        $db->simpleQuery('DELETE FROM conges');
        $db->simpleQuery('DELETE FROM soldes');
        $db->simpleQuery('DELETE FROM employes');
        $db->simpleQuery('DELETE FROM types_conge');
        $db->simpleQuery('DELETE FROM departements');

        // Réinitialiser les auto-increments
        $db->simpleQuery("DELETE FROM sqlite_sequence WHERE name='conges'");
        $db->simpleQuery("DELETE FROM sqlite_sequence WHERE name='soldes'");
        $db->simpleQuery("DELETE FROM sqlite_sequence WHERE name='employes'");
        $db->simpleQuery("DELETE FROM sqlite_sequence WHERE name='types_conge'");
        $db->simpleQuery("DELETE FROM sqlite_sequence WHERE name='departements'");

        // ─── RÉACTIVER LES FOREIGN KEYS ───────────────────────────
        $db->simpleQuery('PRAGMA foreign_keys = ON');

        // ─── 1. TYPES DE CONGÉ ────────────────────────────────────
        $types = [
            ['libelle' => 'Congé annuel',  'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Congé maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['libelle' => 'Congé spécial', 'jours_annuels' => 5,  'deductible' => 1],
            ['libelle' => 'Sans solde',    'jours_annuels' => 0,  'deductible' => 0],
        ];
        $db->table('types_conge')->insertBatch($types);
        echo "✓ Types de congé insérés\n";

        // ─── 2. DÉPARTEMENTS ──────────────────────────────────────
        $depts = [
            ['nom' => 'IT',        'description' => 'Informatique & Systèmes'],
            ['nom' => 'Finance',   'description' => 'Comptabilité & Finance'],
            ['nom' => 'Marketing', 'description' => 'Communication & Marketing'],
            ['nom' => 'RH',        'description' => 'Ressources Humaines'],
        ];
        $db->table('departements')->insertBatch($depts);
        echo "✓ Départements insérés\n";

        // Récupérer les IDs
        $deptIT  = (int) $db->table('departements')->where('nom', 'IT')->get()->getRow()->id;
        $deptRH  = (int) $db->table('departements')->where('nom', 'RH')->get()->getRow()->id;
        $deptFin = (int) $db->table('departements')->where('nom', 'Finance')->get()->getRow()->id;
        $deptMkt = (int) $db->table('departements')->where('nom', 'Marketing')->get()->getRow()->id;

        // ─── 3. EMPLOYÉS ──────────────────────────────────────────
        $employes = [
            [
                'nom'            => 'Système',
                'prenom'         => 'Administrateur',
                'email'          => 'admin@techmada.mg',
                'password'       => password_hash('admin123', PASSWORD_DEFAULT),
                'role'           => 'admin',
                'departement_id' => $deptRH,
                'date_embauche'  => '2020-01-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Rabe',
                'prenom'         => 'Marie',
                'email'          => 'rh@techmada.mg',
                'password'       => password_hash('rh123', PASSWORD_DEFAULT),
                'role'           => 'rh',
                'departement_id' => $deptRH,
                'date_embauche'  => '2021-03-15',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Rakoto',
                'prenom'         => 'Soa',
                'email'          => 'employe@techmada.mg',
                'password'       => password_hash('emp123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => $deptIT,
                'date_embauche'  => '2022-06-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Fidy',
                'prenom'         => 'Tsiry',
                'email'          => 'tsiry@techmada.mg',
                'password'       => password_hash('emp123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => $deptFin,
                'date_embauche'  => '2021-09-10',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Andria',
                'prenom'         => 'Haja',
                'email'          => 'haja@techmada.mg',
                'password'       => password_hash('emp123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => $deptMkt,
                'date_embauche'  => '2023-01-05',
                'actif'          => 1,
            ],
        ];
        $db->table('employes')->insertBatch($employes);
        echo "✓ Employés insérés\n";

        // Récupérer tous les employés
        $allEmployes = $db->table('employes')->get()->getResultArray();

        // ─── 4. SOLDES ────────────────────────────────────────────
        $allTypes = $db->table('types_conge')->where('deductible', 1)->get()->getResultArray();

        $soldes = [];
        foreach ($allEmployes as $emp) {
            foreach ($allTypes as $type) {
                $joursPris = 0;
                if ($emp['role'] === 'employe') {
                    if ($type['libelle'] === 'Congé annuel')  $joursPris = rand(2, 8);
                    if ($type['libelle'] === 'Congé maladie') $joursPris = rand(0, 2);
                }
                $soldes[] = [
                    'employe_id'      => $emp['id'],
                    'type_conge_id'   => $type['id'],
                    'annee'           => $annee,
                    'jours_attribues' => $type['jours_annuels'],
                    'jours_pris'      => $joursPris,
                ];
            }
        }
        $db->table('soldes')->insertBatch($soldes);
        echo "✓ Soldes initialisés pour l'année $annee\n";

        // ─── 5. DEMANDES DE DÉMONSTRATION ─────────────────────────
        $empSoa   = (int) $db->table('employes')->where('email', 'employe@techmada.mg')->get()->getRow()->id;
        $empTsiry = (int) $db->table('employes')->where('email', 'tsiry@techmada.mg')->get()->getRow()->id;
        $empHaja  = (int) $db->table('employes')->where('email', 'haja@techmada.mg')->get()->getRow()->id;
        $empMarie = (int) $db->table('employes')->where('email', 'rh@techmada.mg')->get()->getRow()->id;

        $typeAnnuel  = (int) $db->table('types_conge')->where('libelle', 'Congé annuel')->get()->getRow()->id;
        $typeMaladie = (int) $db->table('types_conge')->where('libelle', 'Congé maladie')->get()->getRow()->id;
        $typeSpecial = (int) $db->table('types_conge')->where('libelle', 'Congé spécial')->get()->getRow()->id;

        // Valeurs statut autorisées : 'en_attente', 'approuve', 'refuse', 'annule'
        $demandesDemo = [
            [
                'employe_id'     => $empSoa,
                'type_conge_id'  => $typeAnnuel,
                'date_debut'     => date('Y-m-d', strtotime('+10 days')),
                'date_fin'       => date('Y-m-d', strtotime('+14 days')),
                'nb_jours'       => 5,
                'motif'          => 'Vacances en famille',
                'statut'         => 'en_attente',
                'commentaire_rh' => null,
                'traite_par'     => null,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'employe_id'     => $empTsiry,
                'type_conge_id'  => $typeMaladie,
                'date_debut'     => date('Y-m-d', strtotime('+5 days')),
                'date_fin'       => date('Y-m-d', strtotime('+6 days')),
                'nb_jours'       => 2,
                'motif'          => 'Consultation médicale',
                'statut'         => 'en_attente',
                'commentaire_rh' => null,
                'traite_par'     => null,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'employe_id'     => $empHaja,
                'type_conge_id'  => $typeAnnuel,
                'date_debut'     => date('Y-m-d', strtotime('+20 days')),
                'date_fin'       => date('Y-m-d', strtotime('+24 days')),
                'nb_jours'       => 5,
                'motif'          => 'Repos',
                'statut'         => 'en_attente',
                'commentaire_rh' => null,
                'traite_par'     => null,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
            [
                'employe_id'     => $empSoa,
                'type_conge_id'  => $typeMaladie,
                'date_debut'     => date('Y-m-d', strtotime('-20 days')),
                'date_fin'       => date('Y-m-d', strtotime('-19 days')),
                'nb_jours'       => 2,
                'motif'          => 'Maladie',
                'statut'         => 'approuve',
                'commentaire_rh' => 'Validé sans problème.',
                'traite_par'     => $empMarie,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-22 days')),
            ],
            [
                'employe_id'     => $empSoa,
                'type_conge_id'  => $typeAnnuel,
                'date_debut'     => date('Y-m-d', strtotime('-40 days')),
                'date_fin'       => date('Y-m-d', strtotime('-36 days')),
                'nb_jours'       => 5,
                'motif'          => 'Voyage',
                'statut'         => 'approuve',
                'commentaire_rh' => 'OK',
                'traite_par'     => $empMarie,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-45 days')),
            ],
            [
                'employe_id'     => $empTsiry,
                'type_conge_id'  => $typeSpecial,
                'date_debut'     => date('Y-m-d', strtotime('-10 days')),
                'date_fin'       => date('Y-m-d', strtotime('-10 days')),
                'nb_jours'       => 1,
                'motif'          => 'Événement personnel',
                'statut'         => 'refuse',
                'commentaire_rh' => 'Chevauchement détecté avec une période critique.',
                'traite_par'     => $empMarie,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-12 days')),
            ],
            [
                'employe_id'     => $empHaja,
                'type_conge_id'  => $typeAnnuel,
                'date_debut'     => date('Y-m-d', strtotime('-5 days')),
                'date_fin'       => date('Y-m-d', strtotime('-3 days')),
                'nb_jours'       => 3,
                'motif'          => 'Déplacement annulé',
                'statut'         => 'annule',
                'commentaire_rh' => "Annulé par l'employé",
                'traite_par'     => null,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
        ];

        $db->table('conges')->insertBatch($demandesDemo);
        echo "✓ Demandes de démonstration insérées\n";

        echo "\n══════════════════════════════════════\n";
        echo "  Seeder terminé avec succès !\n";
        echo "══════════════════════════════════════\n";
        echo "  Comptes de connexion :\n";
        echo "  admin@techmada.mg     / admin123  (admin)\n";
        echo "  rh@techmada.mg        / rh123     (rh)\n";
        echo "  employe@techmada.mg   / emp123    (employe)\n";
        echo "  tsiry@techmada.mg     / emp123    (employe)\n";
        echo "  haja@techmada.mg      / emp123    (employe)\n";
        echo "══════════════════════════════════════\n";
    }
}
<?php

namespace App\DataFixtures\Data;

class IlotData
{
    public static $ilotData = [
        [
            'nom_ax' => 100,
            'nom_irl' => 'Adhésif',
            'nom_url' => 'Adhesif',
            'code_imprimante' => 'ADH',
            'printer' => 'Adhésif'
        ],
        [
            'nom_ax' => 30,
            'nom_irl' => 'Annule & Remplace',
            'nom_url' => 'AnnuleATraiter',
            'code_imprimante' => 'ANN',
            'printer' => 'Test'
        ],
        [
            'nom_ax' => 160,
            'nom_irl' => 'Débit',
            'nom_url' => 'Debit',
            'code_imprimante' => 'DEB',
            'printer' => 'Serrurerie'
        ],
        [
            'nom_ax' => 35,
            'nom_irl' => 'Echantillon',
            'nom_url' => 'Echantillon',
            'code_imprimante' => 'ECH',
            'printer' => 'Test'
        ],
        [
            'nom_ax' => 60,
            'nom_irl' => 'Faces Permanente',
            'nom_url' => 'FacesPerm',
            'code_imprimante' => 'FACP',
            'printer' => 'Magasin'
        ],
        [
            'nom_ax' => 70,
            'nom_irl' => 'Faces Temporaire',
            'nom_url' => 'FacesTempo',
            'code_imprimante' => 'FACT',
            'printer' => 'Magasin'
        ],

        [
            'nom_ax' => 55,
            'nom_irl' => 'Fraisage Permanent',
            'nom_url' => 'FraisagePerm',
            'code_imprimante' => 'FRAP',
            'printer' => 'Fraisage'
        ],
        [
            'nom_ax' => 50,
            'nom_irl' => 'Fraisage Tempo',
            'nom_url' => 'FraisageTempo',
            'code_imprimante' => 'FRAT',
            'printer' => 'Fraisage'
        ],

        /* ##### Laquage ##### */
        [
            'nom_ax' => 132,
            'nom_irl' => 'Laquage Accroche',
            'nom_url' => 'LaqAcc',
            'code_imprimante' => 'LAQACC',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 133,
            'nom_irl' => 'Laquage Panneaux',
            'nom_url' => 'LaqPan',
            'code_imprimante' => 'LAQPAN',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 134,
            'nom_irl' => 'Laquage Supports',
            'nom_url' => 'LaqSup',
            'code_imprimante' => 'LAQSUP',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 140,
            'nom_irl' => 'Laquage en sous-traitance',
            'nom_url' => 'LaqSst',
            'code_imprimante' => 'LAQSST',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 9995,
            'nom_irl' => 'Peinture',
            'nom_url' => 'Peinture',
            'code_imprimante' => 'MOB',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 9996,
            'nom_irl' => 'Laquage',
            'nom_url' => 'Laquage',
            'code_imprimante' => 'LAQ',
            'printer' => 'Laquage'
        ],
        [
            'nom_ax' => 9997,
            'nom_irl' => 'Etiquettes Laquage : RAL',
            'nom_url' => 'Laquage', // LaqImpr ImprEtiqLaqRAL LaqEtiqRAL
            'code_imprimante' => 'LAQRAL',
            'printer' => 'Laquage'
        ],
        [
            'nom_ax' => 9998,
            'nom_irl' => 'Etiquettes Laquage : OF',
            'nom_url' => 'Laquage', // LaqEtiqOF
            'code_imprimante' => 'LAQOF',
            'printer' => 'Laquage'
        ],
        [
            'nom_ax' => 9999,
            'nom_irl' => 'Etiquettes Laquage',
            'nom_url' => 'LaqEtiqHome',
            'code_imprimante' => 'LAQIMPR',
            'printer' => 'Test'
        ],
        /* ##### Laquage ##### */

        [
            'nom_ax' => 120,
            'nom_irl' => 'Magasin',
            'nom_url' => 'Magasin',
            'code_imprimante' => 'MAG',
            'printer' => 'Magasin'
        ],
        [
            'nom_ax' => 40,
            'nom_irl' => 'Mise en Fabrication',
            'nom_url' => 'MiseEnFab',
            'code_imprimante' => 'MEF',
            'printer' => 'Chef Atelier'
        ],
        [
            'nom_ax' => 110,
            'nom_irl' => 'Mobilier Urbain',
            'nom_url' => 'MobilierUrbain',
            'code_imprimante' => 'MOBU',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 10,
            'nom_irl' => 'Création de l\'OF',
            'nom_url' => 'OFATraiter',
            'code_imprimante' => 'OFT',
            'printer' => 'Test'
        ],
        [
            'nom_ax' => 90,
            'nom_irl' => 'Permanente',
            'nom_url' => 'Permanente',
            'code_imprimante' => 'PERM',
            'printer' => 'Magasin'
        ],
        [
            'nom_ax' => 180,
            'nom_irl' => 'Pliage',
            'nom_url' => 'Pliage',
            'code_imprimante' => 'PLI',
            'printer' => 'Temporaire'
        ],
        [
            'nom_ax' => 230,
            'nom_irl' => 'Prépa Permanente',
            'nom_url' => 'PrepaPerm',
            'code_imprimante' => 'PPER',
            'printer' => 'Magasin'
        ],
        [
            'nom_ax' => 20,
            'nom_irl' => 'SAV',
            'nom_url' => 'SAVATraiter',
            'code_imprimante' => 'SAV',
            'printer' => 'Test'
        ],
        [
            'nom_ax' => 170,
            'nom_irl' => 'Serrurerie',
            'nom_url' => 'Serrurerie',
            'code_imprimante' => 'SERR',
            'printer' => 'Serrurerie'
        ],
        [
            'nom_ax' => 80,
            'nom_irl' => 'Temporaire',
            'nom_url' => 'Temporaire',
            'code_imprimante' => 'TEMP',
            'printer' => 'Magasin'
        ]
    ];
}
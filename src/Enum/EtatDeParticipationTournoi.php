<?php
// src/Enum/EtatDeParticipationTournoi.php

namespace App\Enum;

enum EtatDeParticipationTournoi: string
{
    case Inscrit = 'inscrit';
    case EnCours = 'en_cours';
    case Terminé = 'termine';
    case Annulé = 'annule';
}


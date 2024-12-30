<?php
// src/Enum/EtatDeParticipationTournoi.php

namespace App\Enum;

enum EtatDeParticipationTournoi: string
{
    case Inscrit = 'inscrit';
    case EnCours = 'en_cours';
    case Terminé = 'termine';
    case Annulé = 'annule';

    public function getLabel(): string
    {
        return $this->name; // or $this->value depending on what you want to show
    }
}


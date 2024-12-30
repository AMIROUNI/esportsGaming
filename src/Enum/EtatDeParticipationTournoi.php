<?php
// src/Enum/EtatDeParticipationTournoi.php

namespace App\Enum;

enum EtatDeParticipationTournoi: string
{
    case PARTICIPANT = 'participant';
    case WINNER = 'winner';
    case ELIMINATED = 'eliminated';
    case INSCRIT = 'inscrit'; 
}


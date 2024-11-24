<?php
// src/Enum/EtatDeParticipation.php

namespace App\Enum;

enum EtatDeParticipation: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

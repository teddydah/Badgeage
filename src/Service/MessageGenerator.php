<?php

namespace App\Service;

use App\Entity\Ilot;

class MessageGenerator
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessageBadgeage(string $numOF, Ilot $ilot): string
    {
        return $this->message = 'Le badgeage "' . $numOF . '" pour l\'îlot ' . $ilot->getNomIRL() . ' n\'existe pas.';
    }
}
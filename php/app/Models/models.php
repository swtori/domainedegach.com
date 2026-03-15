<?php

class Chambre
{
    private int $id;
    private string $designation;
    private float $prix;
    private int $capaciteMax;

    public function __construct(int $id, string $designation, float $prix, int $capaciteMax)
    {
        $this->id = $id;
        $this->designation = $designation;
        $this->prix = $prix;
        $this->capaciteMax = $capaciteMax;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDesignation(): string
    {
        return $this->designation;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getCapaciteMax(): int
    {
        return $this->capaciteMax;
    }

    /**
     * Représentation textuelle simple de la chambre (pratique pour les tests rapides).
     */
    public function getChambre(): string
    {
        return sprintf(
            '%s - %.2f € - %d pers. max',
            $this->designation,
            $this->prix,
            $this->capaciteMax
        );
    }
}

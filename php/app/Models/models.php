<?php

class Chambre 
{
    private $id;
    private $designation;
    private $prix;
    private $capaciteMax;

    public function __construct($id, $designation, $prix, $capaciteMax)
    {
        $this->id = $id;
        $this->designation = $designation;
        $this->prix = $prix;
        $this->capaciteMax = $capaciteMax;
    }

    public function getChambre(): string
    {
        
    }
}
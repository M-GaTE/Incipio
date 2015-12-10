<?php
        
/*
This file is part of Incipio.

Incipio is an enterprise resource planning for Junior Enterprise
Copyright (C) 2012-2014 Florian Lefevre.

Incipio is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Incipio is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Incipio as the file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
*/


namespace mgate\TresoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BV
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"mandat", "numero"})})
 * @ORM\Entity(repositoryClass="mgate\TresoBundle\Entity\BVRepository")
 */
class BV
{
    // TODO Liée au répartition JEH laisser le choix d'ajouter des existantes (une fois que les avenants seront OK)
    // si plusieur repartition, moyenner
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="mandat", type="smallint")
     */
    private $mandat;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;
        
    /**
     * @var integer
     *
     * @ORM\Column(name="nombreJEH", type="smallint")
     */
    private $nombreJEH;

    /**
     * @var float
     *
     * @ORM\Column(name="remunerationBruteParJEH", type="float")
     */
    private $remunerationBruteParJEH;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDeVersement", type="date")
     */
    private $dateDeVersement;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemission", type="date")
     */
    private $dateDemission;

    /**
     * @var string
     *
     * @ORM\Column(name="typeDeTravail", type="string", length=255)
     */
    private $typeDeTravail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="numeroVirement", type="string", length=255)
     */
    private $numeroVirement;
    
    /**
     * @ORM\ManyToOne(targetEntity="mgate\SuiviBundle\Entity\Mission")
     */
    private $mission;

    /**
     * @ORM\ManyToOne(targetEntity="mgate\TresoBundle\Entity\BaseURSSAF")
     */
    private $baseURSSAF;
    
    /**
     * @ORM\ManyToMany(targetEntity="mgate\TresoBundle\Entity\CotisationURSSAF")
     */
    private $cotisationURSSAF;
    
    
    
    //GETTER ADITION
    public function getReference(){
        return $this->mandat.'-BV-'.sprintf('%1$02d',$this->numero);
    }
    
    public function getRemunerationBrute(){
        return $this->getRemunerationBruteParJEH() * $this->nombreJEH;
    }
    
    public function getAssietteDesCotisations(){
        if ($this->baseURSSAF)
            return $this->baseURSSAF->getBaseURSSAF() * $this->nombreJEH;
        else 
            return null;
    }
    
    public function getRemunerationNet(){
        return $this->getRemunerationBrute() - $this->getPartEtudiant();
    }
    
    public function getRemunerationNetImposable(){
        return $this->getRemunerationBrute() - $this->getPartEtudiant(false, true);
    }
    
    /**
     * 
     * @return array
     */
    public function getTauxPartJunior(){
        $tauxPartJunior= array(
            'baseURSSAF'    => 0,
            'baseBrute'     => 0
        );
        
        foreach ($this->cotisationURSSAF as $cotisation){
            if($cotisation->getIsSurBaseURSSAF() && $this->baseURSSAF)
                $tauxPartJunior['baseURSSAF'] += $cotisation->getTauxPartJE();
            else
                $tauxPartJunior['baseBrute'] += $cotisation->getTauxPartJE();
        }    
        return $tauxPartJunior;
    }
    
    /**
     * 
     * @return array
     */
    public function getTauxPartEtu(){
        $tauxPartEtu= array(
            'baseURSSAF'    => 0,
            'baseBrute'     => 0
        );
        
        foreach ($this->cotisationURSSAF as $cotisation){
            if($cotisation->getIsSurBaseURSSAF() && $this->baseURSSAF)
                $tauxPartEtu['baseURSSAF'] += $cotisation->getTauxPartEtu();
            else
                $tauxPartEtu['baseBrute'] += $cotisation->getTauxPartEtu();
        }    
        return $tauxPartEtu;
    }
    
    /**
     * 
     * @param boolean $inArray
     * @return mixte
     */
    public function getPartJunior($inArray = false){
        $partJunior = array(
            'baseURSSAF'    => 0,
            'baseBrute'     => 0
        );
        foreach ($this->cotisationURSSAF as $cotisation){
            if($cotisation->getIsSurBaseURSSAF() && $this->baseURSSAF)
                $partJunior['baseURSSAF'] += round($this->nombreJEH  * $this->baseURSSAF->getBaseURSSAF() * $cotisation->getTauxPartJE(),2);
            else
                $partJunior['baseBrute'] += round($this->nombreJEH  * $cotisation->getTauxPartJE()* $this->remunerationBruteParJEH,2);
        }
        if($inArray)
            return $partJunior;
        else
            return $partJunior['baseURSSAF'] +  $partJunior['baseBrute'];
            
    }
    
    /**
     * 
     * @param boolean $inArray
     * @return mixte
     */
    public function getPartEtudiant($inArray = false, $nonImposable = false){
        $partEtu = array(
            'baseURSSAF'    => 0,
            'baseBrute'     => 0
        );
        
        foreach ($this->cotisationURSSAF as $cotisation){
            if($nonImposable && !$cotisation->getDeductible()) continue;
            
            if($cotisation->getIsSurBaseURSSAF() && $this->baseURSSAF)
                $partEtu['baseURSSAF'] += round($this->nombreJEH  * $this->baseURSSAF->getBaseURSSAF() * $cotisation->getTauxPartEtu() ,2);
            else
                $partEtu['baseBrute'] += round($this->nombreJEH  * $cotisation->getTauxPartEtu() * $this->remunerationBruteParJEH,2);
        }    
        
        if($inArray)
            return $partEtu;
        else
            return $partEtu['baseURSSAF'] +  $partEtu['baseBrute'];
    }

    
    ///////

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotisationURSSAF = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mandat
     *
     * @param integer $mandat
     * @return BV
     */
    public function setMandat($mandat)
    {
        $this->mandat = $mandat;
    
        return $this;
    }

    /**
     * Get mandat
     *
     * @return integer 
     */
    public function getMandat()
    {
        return $this->mandat;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return BV
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set nombreJEH
     *
     * @param integer $nombreJEH
     * @return BV
     */
    public function setNombreJEH($nombreJEH)
    {
        $this->nombreJEH = $nombreJEH;
    
        return $this;
    }

    /**
     * Get nombreJEH
     *
     * @return integer 
     */
    public function getNombreJEH()
    {
        return $this->nombreJEH;
    }

    /**
     * Set remunerationBruteParJEH
     *
     * @param float $remunerationBruteParJEH
     * @return BV
     */
    public function setRemunerationBruteParJEH($remunerationBruteParJEH)
    {
        $this->remunerationBruteParJEH = $remunerationBruteParJEH;
    
        return $this;
    }

    /**
     * Get remunerationBruteParJEH
     *
     * @return float 
     */
    public function getRemunerationBruteParJEH()
    {
        return $this->remunerationBruteParJEH;
    }

    /**
     * Set dateDeVersement
     *
     * @param \DateTime $dateDeVersement
     * @return BV
     */
    public function setDateDeVersement($dateDeVersement)
    {
        $this->dateDeVersement = $dateDeVersement;
    
        return $this;
    }

    /**
     * Get dateDeVersement
     *
     * @return \DateTime 
     */
    public function getDateDeVersement()
    {
        return $this->dateDeVersement;
    }

    /**
     * Set dateDemission
     *
     * @param \DateTime $dateDemission
     * @return BV
     */
    public function setDateDemission($dateDemission)
    {
        $this->dateDemission = $dateDemission;
    
        return $this;
    }

    /**
     * Get dateDemission
     *
     * @return \DateTime 
     */
    public function getDateDemission()
    {
        return $this->dateDemission;
    }

    /**
     * Set typeDeTravail
     *
     * @param string $typeDeTravail
     * @return BV
     */
    public function setTypeDeTravail($typeDeTravail)
    {
        $this->typeDeTravail = $typeDeTravail;
    
        return $this;
    }

    /**
     * Get typeDeTravail
     *
     * @return string 
     */
    public function getTypeDeTravail()
    {
        return $this->typeDeTravail;
    }

    /**
     * Set numeroVirement
     *
     * @param string $numeroVirement
     * @return BV
     */
    public function setNumeroVirement($numeroVirement)
    {
        $this->numeroVirement = $numeroVirement;
    
        return $this;
    }

    /**
     * Get numeroVirement
     *
     * @return string 
     */
    public function getNumeroVirement()
    {
        return $this->numeroVirement;
    }

    /**
     * Set mission
     *
     * @param \mgate\SuiviBundle\Entity\Mission $mission
     * @return BV
     */
    public function setMission(\mgate\SuiviBundle\Entity\Mission $mission = null)
    {
        $this->mission = $mission;
    
        return $this;
    }

    /**
     * Get mission
     *
     * @return \mgate\SuiviBundle\Entity\Mission 
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set baseURSSAF
     *
     * @param \mgate\TresoBundle\Entity\BaseURSSAF $baseURSSAF
     * @return BV
     */
    public function setBaseURSSAF(\mgate\TresoBundle\Entity\BaseURSSAF $baseURSSAF = null)
    {
        $this->baseURSSAF = $baseURSSAF;
    
        return $this;
    }

    /**
     * Get baseURSSAF
     *
     * @return \mgate\TresoBundle\Entity\BaseURSSAF 
     */
    public function getBaseURSSAF()
    {
        return $this->baseURSSAF;
    }



    /**
     * Add cotisationURSSAF
     *
     * @param \mgate\TresoBundle\Entity\CotisationURSSAF $cotisationURSSAF
     * @return BV
     */
    public function addCotisationURSSAF(\mgate\TresoBundle\Entity\CotisationURSSAF $cotisationURSSAF)
    {
        $this->cotisationURSSAF[] = $cotisationURSSAF;
    
        return $this;
    }

    /**
     * Remove cotisationURSSAF
     *
     * @param \mgate\TresoBundle\Entity\CotisationURSSAF $cotisationURSSAF
     */
    public function removeCotisationURSSAF(\mgate\TresoBundle\Entity\CotisationURSSAF $cotisationURSSAF)
    {
        $this->cotisationURSSAF->removeElement($cotisationURSSAF);
    }

    /**
     * Get cotisationURSSAF
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCotisationURSSAF()
    {
        return $this->cotisationURSSAF;
    }
    
    /**
     * Get cotisationURSSAF
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function setCotisationURSSAF()
    {
        $this->cotisationURSSAF = null;
        return $this;
    }
}
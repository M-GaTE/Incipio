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


namespace mgate\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use mgate\SuiviBundle\Entity\RepartitionJEHRepository as RepartitionJEHRepository;
use mgate\PersonneBundle\Entity\Personne;

/**
 * mgate\SuiviBundle\Entity\Mission
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Mission extends DocType {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Etude", inversedBy="missions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $etude;

    /**
     * @ORM\ManyToOne(targetEntity="mgate\PersonneBundle\Entity\Membre")
     * @ORM\JoinColumn(nullable=true)
     */
    private $referentTechnique;

    
    /**
     * @ORM\ManyToOne(targetEntity="\mgate\PersonneBundle\Entity\Membre", inversedBy="missions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $intervenant;

    /**
     * @var \DateTime $debutOm
     *
     * @ORM\Column(name="debutOm", type="datetime", nullable=true)
     */
    private $debutOm;

    /**
     * @var \DateTime $finOm
     *
     * @ORM\Column(name="finOm", type="datetime", nullable=true)
     */
    private $finOm;

    /**
     * @var float $pourcentageJunior
     *
     * @ORM\Column(name="pourcentageJunior", type="float", nullable=true)
     */
    private $pourcentageJunior;

    /**
     * @var RepartitionJEH $repartitionsJEH
     * @ORM\OneToMany(targetEntity="mgate\SuiviBundle\Entity\RepartitionJEH", mappedBy="mission", cascade={"persist", "merge", "remove"})
     */
    private $repartitionsJEH;

    /**
     * @var integer $avancement
     *
     * @ORM\Column(name="avancement", type="integer", nullable=true)
     */
    private $avancement;

    /**
     * @var boolean $rapportDemande
     *
     * @ORM\Column(name="rapportDemande", type="boolean", nullable=true)
     */
    private $rapportDemande;

    /**
     * @var boolean $rapportRelu
     *
     * @ORM\Column(name="rapportRelu", type="boolean", nullable=true)
     */
    private $rapportRelu;

    /**
     * @var boolean $remunere
     *
     * @ORM\Column(name="remunere", type="boolean", nullable=true)
     */
    private $remunere;

//Ajout fonction rapide
    public function getReference(){
        if($this->getIntervenant())
            return $this->getEtude()->getReference().'-RM-'.$this->getIntervenant()->getIdentifiant().'-'.$this->getVersion();
        else
            return $this->getEtude()->getReference().'-RM-XYn-'.$this->getVersion();
    }
    
    /**
     * @deprecated since version 0.1
     * @return array('jehRemuneration','montantRemuneration');
     */
    public function getRemuneration() {
        $nbrJEHRemuneration = (int) 0;
        $prixRemuneration = (float) 0;
        foreach ($this->getRepartitionsJEH() as $repartitionJEH){
            $nbrJEHRemuneration += $repartitionJEH->getNbrJEH();
            $prixRemuneration += $repartitionJEH->getNbrJEH() * $repartitionJEH->getPrixJEH();
        }
        $prixRemuneration *= 1 - $this->getPourcentageJunior();            
            
        return array('jehRemuneration' => $nbrJEHRemuneration, 'montantRemuneration' => $prixRemuneration);
    }
    
    public function getRemunerationBrute() {
        $prixRemuneration = (float) 0;
        foreach ($this->getRepartitionsJEH() as $repartitionJEH){
            $prixRemuneration += $repartitionJEH->getNbrJEH() * $repartitionJEH->getPrixJEH();
        }
        $prixRemuneration *= 1 - $this->getPourcentageJunior();            
            
        return $prixRemuneration;
    }
    
    public function getNbrJEH(){
        $nbr = 0;
        foreach ($this->repartitionsJEH as $repartition)
            $nbr += $repartition->getNbrJEH();
        return $nbr;
    }

//Block astuce pour ajout direct d'intervenant dans formulaire
    public function getMission() {
        return $this;
    }

    private $knownIntervenant = false;
    private $newIntervenant;

    public function isKnownIntervenant() {
        return $this->knownIntervenant;
    }

    public function setKnownIntervenant($boolean) {
        $this->knownIntervenant = $boolean;
    }

    public function getNewIntervenant() {
        return $this->newIntervenant;
    }

    public function setNewIntervenant($var) {
        $this->newIntervenant = $var;
    }

// Fin du block 

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set intervenant
     *
     * @param mgate\PersonneBundle\Entity\Membre $intervenant
     * @return Mission
     */
    public function setIntervenant(\mgate\PersonneBundle\Entity\Membre $intervenant) {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return mgate\PersonneBundle\Entity\Membre
     */
    public function getIntervenant() {
        return $this->intervenant;
    }

    /**
     * Set debutOm
     *
     * @param \DateTime $debutOm
     * @return Mission
     */
    public function setDebutOm($debutOm) {
        $this->debutOm = $debutOm;

        return $this;
    }

    /**
     * Get debutOm
     *
     * @return \DateTime 
     */
    public function getDebutOm() {
        return $this->debutOm;
    }

    /**
     * Set finOm
     *
     * @param \DateTime $finOm
     * @return Mission
     */
    public function setFinOm($finOm) {
        $this->finOm = $finOm;

        return $this;
    }

    /**
     * Get finOm
     *
     * @return \DateTime 
     */
    public function getFinOm() {
        return $this->finOm;
    }

    /**
     * Set avancement
     *
     * @param integer $avancement
     * @return Mission
     */
    public function setAvancement($avancement) {
        $this->avancement = $avancement;

        return $this;
    }

    /**
     * Get avancement
     *
     * @return integer 
     */
    public function getAvancement() {
        return $this->avancement;
    }

    /**
     * Set rapportDemande
     *
     * @param boolean $rapportDemande
     * @return Mission
     */
    public function setRapportDemande($rapportDemande) {
        $this->rapportDemande = $rapportDemande;

        return $this;
    }

    /**
     * Get rapportDemande
     *
     * @return boolean 
     */
    public function getRapportDemande() {
        return $this->rapportDemande;
    }

    /**
     * Set rapportRelu
     *
     * @param boolean $rapportRelu
     * @return Mission
     */
    public function setRapportRelu($rapportRelu) {
        $this->rapportRelu = $rapportRelu;

        return $this;
    }

    /**
     * Get rapportRelu
     *
     * @return boolean 
     */
    public function getRapportRelu() {
        return $this->rapportRelu;
    }

    /**
     * Set remunere
     *
     * @param boolean $remunere
     * @return Mission
     */
    public function setRemunere($remunere) {
        $this->remunere = $remunere;

        return $this;
    }

    /**
     * Get remunere
     *
     * @return boolean 
     */
    public function getRemunere() {
        return $this->remunere;
    }

    /**
     * Set etude
     *
     * @param mgate\SuiviBundle\Entity\Etude $etude
     * @return Mission
     */
    public function setEtude(\mgate\SuiviBundle\Entity\Etude $etude) {
        $this->etude = $etude;

        return $this;
    }

    /**
     * Get etude
     *
     * @return mgate\SuiviBundle\Entity\Etude 
     */
    public function getEtude() {
        return $this->etude;
    }

    
    /**
     * Set pourcentageJunior
     *
     * @param integer $pourcentageJunior
     * @return Phase
     */
    public function setPourcentageJunior($pourcentageJunior) {
        $this->pourcentageJunior = $pourcentageJunior;

        return $this;
    }

    /**
     * Get pourcentageJunior
     *
     * @return integer 
     */
    public function getPourcentageJunior() {
        return $this->pourcentageJunior;
    }

    /**
     * Set referentTechnique
     *
     * @param \mgate\PersonneBundle\Entity\Membre $referentTechnique
     * @return Mission
     */
    public function setReferentTechnique(\mgate\PersonneBundle\Entity\Membre $referentTechnique = null) {
        $this->referentTechnique = $referentTechnique;

        return $this;
    }

    /**
     * Get referentTechnique
     *
     * @return \mgate\PersonneBundle\Entity\Membre 
     */
    public function getReferentTechnique() {
        return $this->referentTechnique;
    }

    /**
     * Add repartitionsJEH
     *
     * @param \mgate\SuiviBundle\Entity\RepartitionJEH $repartitionsJEH
     * @return Mission
     */
    public function addRepartitionsJEH(\mgate\SuiviBundle\Entity\RepartitionJEH $repartitionsJEH) {
        $this->repartitionsJEH[] = $repartitionsJEH;

        return $this;
    }

    /**
     * Remove repartitionsJEH
     *
     * @param \mgate\SuiviBundle\Entity\RepartitionJEH $repartitionsJEH
     */
    public function removeRepartitionsJEH(\mgate\SuiviBundle\Entity\RepartitionJEH $repartitionsJEH) {
        $this->repartitionsJEH->removeElement($repartitionsJEH);
    }

    /**
     * Get repartitionsJEH
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRepartitionsJEH() {
        return $this->repartitionsJEH;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->repartitionsJEH = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pourcentageJunior = 0.4;
    }
    
}
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


namespace mgate\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
          
        $user = $this->container->get('security.context')->getToken()->getUser()->getPersonne();
        
        //Etudes Suiveur
        $etudesSuiveur = array();
        foreach($em->getRepository('mgateSuiviBundle:Etude')->findBy(array('suiveur' => $user), array('mandat'=> 'DESC', 'id'=> 'DESC')) as $etude)
        {
            $stateID = $etude->getStateID();
            if( $stateID <= 2 )
             array_push($etudesSuiveur, $etude);
        }

        $etudesEnCours = $em->getRepository('mgateSuiviBundle:Etude')->findBy(array('stateID' => 2), array('mandat' => 'DESC', 'num' => 'DESC'));
        return $this->render('mgateDashboardBundle:Default:index.html.twig', array("etudesEnCours" => $etudesEnCours,'etudesSuiveur' => $etudesSuiveur));
    }


    public function navbarAction($route = "")
    {
        $em = $this->getDoctrine()->getManager();
          
        $user = $this->container->get('security.context')->getToken()->getUser()->getPersonne();
        
        //Etudes Suiveur
        $etudesSuiveur = array();
        foreach($em->getRepository('mgateSuiviBundle:Etude')->findBy(array('suiveur' => $user), array('mandat'=> 'DESC', 'id'=> 'DESC')) as $etude)
        {
            $stateID = $etude->getStateID();
            if( $stateID <= 2 )
             array_push($etudesSuiveur, $etude);
        }
        
        return $this->render('mgateDashboardBundle:Navbar:layout.html.twig', array('etudesSuiveur' => $etudesSuiveur, "route" => $route,true));
    }

    public function mgateCSSAction(){
        $navbarTop = "#c52000"; //$this->container->getParameter('color_top');
        $navbarTopLight = $this->sumColor($navbarTop,"#FFFFFF",0.2);
        $colorTopVeryLight = $this->sumColor($navbarTop,"#FFFFFF",0.4);
        $footer = $this->sumColor($navbarTop,"#FFFFFF",0.8);
        $sidebar = $this->sumColor($navbarTop,"#FFFFFF",0.8);
        $link = $this->sumColor($navbarTop,"#000000",0.8);
        $sidebarVeryLight = $this->sumColor($navbarTop,"#FFFFFF",0.9);
        return $this->render('mgateDashboardBundle::mgate.css.twig',array(
            'colorTop' => $navbarTop,
            'colorTopLight' => $navbarTopLight,
            'colorTopVeryLight' => $colorTopVeryLight,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'link' => $link,
            'sidebarVeryLight' => $sidebarVeryLight,
            ));
        /*return new Response(var_dump(array(
            'colorTop' => $navbarTop,
            'colorTopLight' => $navbarTopLight,
            'colorTopVeryLight' => $colorTopVeryLight,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'link' => $link,
            'sidebarVeryLight' => $sidebarVeryLight,
            )));*/
    }

    protected function sumColor($color1,$color2,$ratio){
        list($r1,$g1,$b1) = str_split(substr($color1,1),2);
        list($r2,$g2,$b2) = str_split(substr($color2,1),2);
        $r = hexdec($r1)*(1-$ratio) + hexdec($r2)*$ratio;
        $g = hexdec($g1)*(1-$ratio) + hexdec($g2)*$ratio;
        $b = hexdec($b1)*(1-$ratio) + hexdec($b2)*$ratio;
        return "#".str_pad(dechex($r),2,"0",STR_PAD_LEFT).str_pad(dechex($g),2,"0",STR_PAD_LEFT).str_pad(dechex($b),2,"0",STR_PAD_LEFT);
    }
}

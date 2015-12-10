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


namespace mgate\PersonneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilder;

class MandatType extends AbstractType {

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
                ->add('debutMandat', 'genemu_jquerydate', array('label' => 'Date de début', 'format' => 'dd/MM/yyyy', 'required' => false, 'widget' => 'single_text'))
                ->add('finMandat', 'genemu_jquerydate', array('label' => 'Date de Fin', 'format' => 'dd/MM/yyyy', 'required' => false, 'widget' => 'single_text'))
                ->add('poste', 'entity', array('label' => 'Intitulé',
                    'class' => 'mgate\\PersonneBundle\\Entity\\Poste',
                    'property' => 'intitule',
                    'required' => false,));
    }

    public function getName() {
        return 'mgate_personnebundle_mandatetype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'mgate\PersonneBundle\Entity\Mandat',
        ));
    }

}


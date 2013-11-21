<?php

namespace Jtc\AnnonceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Jtc\AnnonceBundle\Entity\Annonce;

class LoadAnnonceData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $fabrice = $this->getReference('fabrice');
        $jean = $this->getReference('jean');
        $vincent = $this->getReference('vincent');
        
        $statuses = $this->container->getParameter('annonce.status');
        $status = $statuses['visible'];
        
        $types = $this->container->getParameter('annonce.type');
        $type = $types['transporteur'];
        $type2 = $types['expediteur'];
        
        $date1 = new \DateTime();
        $date2 = new \DateTime();
        $date3 = new \DateTime();
        $date4 = new \DateTime();
        $date5 = new \DateTime();
        $date2->add(new \DateInterval('P2D'));
        $date3->add(new \DateInterval('P5D'));
        $date4->add(new \DateInterval('P10D'));
        $date5->add(new \DateInterval('P20D'));
        
        $villes = array(
            'Paris',
            'Alger',
            'Tunis',
            'Dakar',
            'Porto-Novo',
            'Cotonou',
            'Le Caire',
            'Rome',
            'Londres',
            'Tokyo',
        );
        
        $this->createAnnonce($manager, $status, $fabrice, $type, $date1, $villes[0], $villes[1], "Très sérieux", 12, 5);
        $this->createAnnonce($manager, $status, $jean, $type, $date2, $villes[0], $villes[2], "Coucou", 5, 2);
        $this->createAnnonce($manager, $status, $vincent, $type, $date3, $villes[0], $villes[3], "Description inutile", 0, 10);
        $this->createAnnonce($manager, $status, $fabrice, $type, $date2, $villes[4], $villes[5]);
        $this->createAnnonce($manager, $status, $fabrice, $type2, $date3, $villes[6], $villes[4]);
        $this->createAnnonce($manager, $status, $fabrice, $type2, $date5, $villes[8], $villes[9]);
        $this->createAnnonce($manager, $status, $jean, $type2, $date4, $villes[8], $villes[9]);
        $this->createAnnonce($manager, $status, $jean, $type, $date1, $villes[5], $villes[3]);
        $this->createAnnonce($manager, $status, $jean, $type2, $date2, $villes[8], $villes[1]);
        $this->createAnnonce($manager, $status, $vincent, $type, $date4, $villes[0], $villes[7]);
        $this->createAnnonce($manager, $status, $vincent, $type2, $date5, $villes[0], $villes[8]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function createAnnonce(ObjectManager $manager, $statut, $utilisateur, $type, $dateDepart, $villeDepart, $villeArrive, $description = null, $poids = null, $prix = null)
    {
        $annonce = new Annonce();
        
        $annonce->setStatut($statut);
        $annonce->setUtilisateur($utilisateur);
        $annonce->setType($type);
        
        $annonce->setDateDepart($dateDepart);
        $annonce->setVilleDepart($villeDepart);
        $annonce->setVilleArrive($villeArrive);
        $annonce->setDescription($description);
        $annonce->setPoids($poids);
        $annonce->setPrix($prix);

        $manager->persist($annonce);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }
}
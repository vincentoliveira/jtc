<?php

namespace Jtc\AnnonceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Jtc\UserBundle\Entity\User;

/**
 * AnnonceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnnonceRepository extends EntityRepository {

    /**
     * Reccupère les dernières annonces
     * @return array
     */
    public function getLastAnnonce($type = null, $limit = null) {
        $qb = $this->createQueryBuilder('a');
        if ($type !== null) {
            $qb->where('a.type = :type')->setParameter('type', $type);
        }
        $qb->andWhere('a.utilisateur IS NOT NULL');
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        $qb->andWhere('a.dateDepart > :today')->setParameter('today', new \DateTime());

        $qb->add('orderBy', 'a.dateMaj DESC');
        return $qb->getQuery()->getResult();
    }

    /**
     * Reccupère les annonce qui partent dans la journée
     * @return array
     */
    public function getLeavingToday() {
        $now = new \DateTime();
        $now2 = new \DateTime();
        $datePivot = $now->setTime(0, 0);
        $datePivot2 = $now2->setTime(23, 59, 59);

        $queryBuilder = $this->createQueryBuilder('annonce');
        $queryBuilder->select('annonce')
            ->where('annonce.dateDepart BETWEEN :datePivot AND :datePivot2')
            ->setParameter(':datePivot', $datePivot)
            ->setParameter(':datePivot2', $datePivot2);
        $queryBuilder->andWhere('annonce.utilisateur IS NOT NULL');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * 
     * @param type $user
     * @return type
     */
    public function getAnnoncesFromUser($userId, $statutId = null) 
    {
        $qb = $this->createQueryBuilder('a');
        $qb
                ->where('a.utilisateur = :user')
                ->setParameter(':user', $userId)
                ->orderBy('a.dateMaj', 'DESC');

        if ($statutId !== null) {
            $qb
                    ->andWhere('a.statut = :statusId')
                    ->setParameter(':statusId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

    public function doSearch($data) 
    {
        $qb = $this->createQueryBuilder('a');
        if ($data['date_depart'] != '') {
            $date = date('Y-m-d H:i:s', strtotime($data['date_depart']));
            $qb->where('a.dateDepart = :date_depart')
                    ->setParameter('date_depart', $date);
        }

        if ($data['ville_depart'] != "") {
            $qb->andWhere('a.villeDepart = :ville_depart')
                    ->setParameter('ville_depart', $data['ville_depart']);
        }


        if ($data['ville_arrive'] != "") {
            $qb->andWhere('a.villeArrive = :ville_arrive')
                    ->setParameter('ville_arrive', $data['ville_arrive']);
        }

        if ($data['poids'] != "") {
            $qb->andWhere('a.poids = :poids')
                    ->setParameter('poids', $data['poids']);
        }

        if ($data['prix'] != "") {
            $qb->andWhere('a.prix = :prix')
                    ->setParameter('prix', $data['prix']);
        }

        if ($data['type'] != "") {
            $qb->andWhere('a.type = :type')
                    ->setParameter('type', $data['type']);
        }

        if (isset($data['type_colis']) and $data['type_colis'] != "") {
            $qb->andWhere('a.typeColis = :type_colis')
                    ->setParameter('type_colis', $data['type_colis'] );
        }

        if (isset($data['type_transport']) and  $data['type_transport']  != "") {
            $qb->andWhere('a.typeTransport = :type_transport')
                    ->setParameter('type_transport', $data['type_transport']);
        }

        return $qb;
    }

}

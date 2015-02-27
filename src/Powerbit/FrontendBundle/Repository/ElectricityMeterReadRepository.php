<?php

namespace Powerbit\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ElectricityMeterReadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ElectricityMeterReadRepository extends EntityRepository {

    public function getAllAsArray() {

        $matchedReads = $this->findAll();

        $matchedReadsArray = array();

        foreach ($matchedReads as $matchedRead)
            $matchedReadsArray[] = $matchedRead->toArray();

        return $matchedReadsArray;
    }

    public function getPrevious($date) {
        $query = $this->createQueryBuilder('e')
                ->where("e.date < :date  ")
                ->setMaxResults(1)
                ->setParameter('date', $date)
                ->orderBy('e.id', 'DESC')
                ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getNext($date) {
        $query = $this->createQueryBuilder('e')
                ->where("e.date > :date  ")
                ->setMaxResults(1)
                ->setParameter('date', $date)
                ->orderBy('e.id', 'ASC')
                ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * 
     * @param type $startDate
     * @param type $endDate
     */
    public function getAllWithMatchedDateRange($startDate, $endDate) {
        $query = $this->createQueryBuilder('e')
                ->where("(e.date >= :startDate) AND (e.date <= :endDate ) ")
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->orderBy('e.date', 'ASC')
                ->getQuery();

        return $query->getResult();
    }

    /**
     * 
     * @param date $startDate
     * @param date $endDate
     * @return array
     */
    public function getAllWithMatchedDateRangeAsArray($startDate, $endDate) {
        $query = $this->createQueryBuilder('e')
                ->where("(e.date >= :startDate) AND (e.date <= :endDate ) ")
                ->setParameter('startDate', ($startDate))
                ->setParameter('endDate', ($endDate))
                ->orderBy('e.date', 'ASC')
                ->getQuery();

        $matchedReads = $query->getResult();

        $matchedReadsArray = array();

        foreach ($matchedReads as $matchedRead)
            $matchedReadsArray[] = $matchedRead->toArray();

        return $matchedReadsArray;
    }

}
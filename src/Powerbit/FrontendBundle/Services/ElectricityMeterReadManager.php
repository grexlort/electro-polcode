<?php

namespace Powerbit\FrontendBundle\Services;

use Doctrine\ORM\EntityManager;

class ElectricityMeterReadManager {

    /**
     * @var EntityManager 
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    /**
     * Zadanie 1 - zwraca przygotowaną tablice w formacie array('date' => 'read'))
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array/null
     */
    public function getDatesArray($startDate, $endDate) {
        $electricityMeterReadRepo = $this->getElectricityMeterReadRepo();
        $meterReads = $electricityMeterReadRepo->getAllWithMatchedDateRangeAsArray($startDate, $endDate);

        if (!empty($meterReads)) {

            $firstKey = $meterReads[0]['date']->format('Y-m-d');
            if ($firstKey != $startDate) {
                $extraDate = $electricityMeterReadRepo->getPrevious($firstKey);
                if ($extraDate) {
                    array_unshift($meterReads, $extraDate->toArray());
                }
            }

            $lastKey = end($meterReads)['date']->format('Y-m-d');
            if ($lastKey != $endDate) {
                $extraDate = $electricityMeterReadRepo->getNext($lastKey);
                if ($extraDate) {
                    $meterReads[] = $extraDate->toArray();
                }
            }
        }
        else {
            $meterReads = [];
            $meterReads[] = $electricityMeterReadRepo->getPrevious($startDate)->toArray();
            $meterReads[] = $electricityMeterReadRepo->getNext($endDate)->toArray();
        }
        
        return ($this->convertArray($meterReads));
    }

    /**
     * Zadanie 2 - zwraca tablice array('date' => 'avg') wszystkich dni, 
     * mieszczących się w przedziale <$startDate, $endDate> z przyporządkowanymi średnimi zużycia prądu.
     * 
     * @param array $meterReads
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAveragesArray(array $meterReads, $startDate, $endDate) {

        $rangesDaysArray = [];
        $rangesArray = $this->getDateRange($meterReads, $startDate, $endDate);

        foreach ($rangesArray as $key => $date) {
            if ($endDate == $date['rangeEnd']) {
                $rangesDaysArray = array_merge(
                        (array) $rangesDaysArray, (array) $this->getDatesFromRange($date['rangeStart'], $date['rangeEnd'], true, $date['avg']));
            }
            else {
                $rangesDaysArray = array_merge(
                        (array) $rangesDaysArray, (array) $this->getDatesFromRange($date['rangeStart'], $date['rangeEnd'], true, $date['avg']));
            }
        }

        return $rangesDaysArray;
    }

    /**
     * Sprawdza czy zakres podany przez usera jest zgodny z logiką
     * @param string $startDate
     * @param string $endDate
     * @return boolean
     */
    public function checkRange($startDate, $endDate) {
        if (( $startDate >= 1388534400 && $startDate <= 1419984000) && ( $endDate
                >= 1388534400 && $endDate <= 1419984000) && $endDate > $startDate
                + 86400) {
            return true;
        }
        else
            return false;
    }

    /**
     * Zwraca sformatowana tablicę w postaci array('date' => 'read')
     * @param array $meterReads
     * @return array
     */
    private function convertArray(array $meterReads) {
        $matchedMeterReadsArray = [];

        foreach ($meterReads as $matchedRead) {
            $key = ($matchedRead['date']->format('Y-m-d'));
            $matchedMeterReadsArray[$key] = $matchedRead['read'];
        }

        return $matchedMeterReadsArray;
    }

    /**
     * Zwraca tablice z kolejnymi zakresami dat wraz z obliczonymi róznicami
     * @param array $meterReads
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getDateRange(array $meterReads, $startDate, $endDate) {

        $i = 0;
        $daysWithRanges = [];
        $rangeDaysArray = array_keys($meterReads);

        while ($i != count($rangeDaysArray) - 1) {

            $rangeStart = $rangeDaysArray[$i];
            $rangeEnd = $rangeDaysArray[$i + 1];

            // pierwsza data to zawsze $startdate
            if ($i == 0) {
                $rangeStart = $startDate;
            }
            // ostatnia data to zawsze $endDate
            if ($i == count($rangeDaysArray) - 2) {
                $rangeEnd = $endDate;
            }

            $dStart = new \DateTime($rangeDaysArray[$i]);
            $dEnd = new \DateTime($rangeDaysArray[$i + 1]);
            $dDiff = $dStart->diff($dEnd);
            $readsDiff = $meterReads[$rangeDaysArray[$i + 1]] - $meterReads[$rangeDaysArray[$i]];
            $daysWithRanges[] = [
                'rangeStart' => $rangeStart,
                'rangeEnd' => $rangeEnd,
                'daysDiff' => $dDiff->days,
                'readsDiff' => $readsDiff,
                'avg' => round(($readsDiff / $dDiff->days), 2)
            ];
            $i++;
        }

        return $daysWithRanges;
    }

    /**
     * Zwraca tablice z dniami w przedziale <$startDate, $endDate> w postaci array(day,day, ...).
     * @param string $startDate
     * @param string $endDate
     * @param bool $addDay - gdy $addDay == false zwraca zakres dat <$startDate, $endDate)
     * @param double $avg - gdy $avg != null zwraca tablice w postaci array('date' => 'avg')
     * @return array
     */
    private function getDatesFromRange($startDate, $endDate, $addDay = true, $avg = null) {
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod(
                new \DateTime($startDate), $interval, $addDay ? (new \DateTime($endDate))->add($interval) : new \DateTime($endDate)
        );

        $array = [];

        foreach ($period as $date) if ($avg)
                $array[$date->format('Y-m-d')] = $avg;
            else
                $array[] = $date->format('Y-m-d');

        return $array;
    }

    /**
     * Shortcut do repo
     * @return ElectricityMeterReadRepository
     */
    private function getElectricityMeterReadRepo() {
        return $this->em->getRepository('PowerbitFrontendBundle:ElectricityMeterRead');
    }

}

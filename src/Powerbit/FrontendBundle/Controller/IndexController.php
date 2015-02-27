<?php

namespace Powerbit\FrontendBundle\Controller;

use Doctrine\ORM\EntityManager;
use Powerbit\FrontendBundle\Form\DateRangeType;
use Powerbit\FrontendBundle\Services\ElectricityMeterReadManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\DiExtraBundle\Annotation\Inject;
use FOS\RestBundle\Controller\Annotations\RequestParam;

class IndexController extends Controller {

    /**
     * @var EntityManager
     * @Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * @var ElectricityMeterReadManager
     * @Inject("powerbit_frontend.electricityMeterReadManager")
     */
    private $electricityMeterReadManager;

    /**
     * @ApiDoc(
     *     description = "Get days in range",
     *     section = "dateRange",
     *     statusCodes={
     *          200="ok",
     *          400="bad"
     *     }
     * )
     * @RequestParam( name="startDate" , requirements="int" , nullable=false  , description="startRange")
     * @RequestParam( name="endDate" , requirements="int" , nullable=false  , description="endRange")
     * @Route("/api/dates")
     * @Method({"GET"})
     */
    public function indexAction(Request $request) {

        $startDate = intval($request->get('startDate'));
        $endDate = intval($request->get('endDate'));

        if (!is_int($startDate) || !is_int($endDate)) {
            throw $this->createNotFoundException();
        }

        if ($this->electricityMeterReadManager->checkRange($startDate, $endDate)) {
            $startDate = date('Y-m-d', $startDate);
            $endDate = date('Y-m-d', $endDate);

            $matchedArray = $this->electricityMeterReadManager->getDatesArray($startDate, $endDate);
            $averageArray = $this->electricityMeterReadManager->getAveragesArray($matchedArray, $startDate, $endDate);

            return new JsonResponse($averageArray, 200);
        }
        else
            throw $this->createNotFoundException();


//        $form = $this->createForm(new DateRangeType(), null, array('method' => 'GET'));
//        $form->bind($request);
//        print_r($form->getData());
//        print_r($request->get('startDate'));
//        print_r($form->get('startDate')->getData());
//        print_r($form->get('endDate')->getData());
//        print_r($form->getErrorsAsString());
//        if ($form->isValid()) {
//        }
//        else {
//            print_r($form->getErrorsAsString());
//            return new JsonResponse('zleee', 400);
//        }



        $matchedArray = $this->electricityMeterReadManager->getDatesArray($startDate, $endDate);
        $averageArray = $this->electricityMeterReadManager->getAveragesArray($matchedArray, $startDate, $endDate);

        return new JsonResponse($averageArray, 200);
    }

    /**
     * @Route("/test")
     * @Method({"GET"})
     */
    public function testAction() {
//        header('Content-Type:text/plain');
        $startDate = '2014-01-08';
        $endDate = '2014-01-15';

        $matchedArray = $this->electricityMeterReadManager->getDatesArray($startDate, $endDate);

//        print_r($matchedArray);


        $averageArray = $this->electricityMeterReadManager->getAveragesArray($matchedArray, $startDate, $endDate);


//        print_r($averageArray);
//        die();

        return new JsonResponse($averageArray, 200);
    }

}

/*

ONIŻEJ ZNAJDZIESZ ZRZUT TABELI Z BAZY MYSQL Z ODCZYTAMI Z WIRTUALNEGO LICZNIKA PRĄDU.
 *  TABELA POSIADA 3 KOLUMNY: ID ODCZYTU, WARTOŚĆ WYRAŻONA W KWH) ORAZ DATĘ ODCZYTU.
 *  TWOIM ZADANIEM BĘDZIE NAPISANIE SKRYPTU W JĘZYKU PHP, KTÓRY ODCZYTA I PRZETWORZY TE DANE.

1. NAPISZ KOD/FUNKCJĘ/KLASĘ (WG UZNANIA),
 *  KTÓRA BĘDZIE MIAŁA ZA ZADANIE ODBIERAĆ Z TABELI ODCZYTY Z DANEGO OKRESU 
 *  I ZWRACAĆ JE W FORMIE TABLICY ASOCJACYJNEJ (DATA => ODCZYT).

ZAKŁADAMY ŻE GODZINY ODCZYTÓW NIE SĄ WAŻNE - NA POTRZEBY PÓŹNIEJSZYCH ZADAŃ MOŻESZ ZAŁOŻYĆ, 
 * ŻE ODCZYTY MIAŁY MIEJSCE ZAWSZE O 00:00:00 DANEGO DNIA. 
 * JEDNEGO DNIA MOŻE BYĆ TYKO JEDEN ODCZYT. UWAGA! 
 * JEŚLI WYBIERZEMY DATY OD 10 STYCZNIA DO 20 STYCZNIA 
 * TO SKRYPT POWINIEN RÓWNIEŻ POBRAĆ OSTATNI ODCZYT Z PRZED 10 STYCZNIA 
 * (JEŚLI NIE BYŁO TEGO DNIA ODCZYTU - JEST TO POTRZEBNE DO WYKONANIA KOLEJNEGO PUNKTU).
 *  DLA UŁATWIENIA ZAŁÓŻ, ŻE DATY MOGĄ BYĆ TYLKO Z 2014 ROKU.

NP. DLA WYBRANEGO PRZEDZIAŁU OD 2014-01-15 DO 2014-02-15 POWINIEN ZWRÓCIĆ
ARRAY('2014-01-01'=>0,'2014-01-30'=>149.90,'2014-02-15'=>222.22)

2. NAPISZ KOD/FUNKCJĘ/KLASĘ (WG UZNANIA),
 *  KTÓRA UŻYWAJĄC TABLICY ZWRÓCONEJ W PKT.
 *  1 ZWRÓCI TABLICĘ ZAWIERAJĄCĄ ŚREDNIE ZUŻYCIE ENERGII
 *  (Z DOKŁADNOŚCIĄ DO 2 MIEJSC PO PRZECINKU) DLA KAŻDEGO DNIA Z DANEGO PRZEDZIAŁU.

DLA UŁATWIENIA ZAŁÓŻ ŻE DATY MOGĄ BYĆ TYLKO Z 2014 ROKU.

NP. MAJĄC TRZY PRZYKŁADOWE ODCZYTY 
 * 1. STYCZNIA 2014 - 200KWH, 
 * 11. STYCZNIA 2014 - 300KWH 
 * 21. STYCZNIA 2014R - 350KWH.
CHCEMY POZNAĆ ŚREDNIE DZIENNE ZUŻYCIE ENERGII DLA PRZEDIAŁU 8 DO 15 STYCZNIA:
ROZWIĄZANIE: MIĘDZY 1 A 11 STYCZNIA ZUŻYTO W SUMIE 100KWH - 
 * BIORĄC POD UWAGĘ ŻE BYŁO TO 10 DNI, TO DZIENNE ŚREDNIE ZUŻYCIE WYNIOSŁO 10KWH
MIĘDZY 11 A 21 SYCZNIA ZUŻYTO W SUMIE 50KWH - 
 * RÓWNIEŻ BYŁO TO 10 DNI WIĘC DZIENNE ŚREDNIE ZUŻYCIE WYNIOSŁO 5KWH,
 *  WIĘC KOD POWINIEN ZWRÓCIĆ TABLICĘ
ARRAY( '2015-01-08'=>10.0,'2015-01-09'=>10.0,
 * '2015-01-10'=>10.0,'2015-01-11'=>5.0,'2015-01-12'=>5.0,'2015-01-13'=>5.0,
 * '2015-01-14'=>5.0,'2015-01-15'=>5.0)

3. (NIEOBOWIĄZKOWE) - WYBIERZ DOWOLNY SYSTEM WYKRESÓW NAPISANY W JĘZYKU JAVASCRIPT (NP. CHART.JS, HIGHCHARTS ITP.) I POKAŻ WYKRES ŚREDNIEGO DZIENNEGO ZUŻYCIA PRĄDU W DANYM PRZEDZIALE (WG DANYCH Z PKT 2.)

ROZWIĄZANIEM NIECH BĘDZIE JEDEN PLIK PHP LUB PACZKA W FORMACIE ZIP (W PRZYPADKU JEŚLI TWOJE ROZWIĄZANIE BĘDZIE ZAWIERAŁO WIĘCEJ NIŻ 1 PLIK). WYŚLIJ JE NA ADRES POLCODE.HR@POLCODE.NET.

NA ZGŁOSZENIA I ROZWIĄZANIA ZADAŃ CZEKAMY DO 28 LUTEGO 2015 R.

-------------------------------------------------------------

CREATE TABLE `ELECTRICITY_METER_READS` (
`ID` INT(11) NOT NULL AUTO_INCREMENT,
`READ` DECIMAL(10,2) NOT NULL,
`DATE` DATE NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=INNODB;

INSERT INTO `ELECTRICITY_METER_READS` (`ID`, `READ`, `DATE`) VALUES
(1, 0.00, '2014-01-01'),
(2, 149.90, '2014-01-30'),
(3, 222.22, '2014-02-15'),
(4, 340.10, '2014-03-03'),
(5, 552.99, '2014-04-15'),
(6, 670.04, '2014-05-10'),
(7, 920.24, '2014-07-01'),
(8, 1000.01,'2014-07-15'),
(9, 1060.40,'2014-08-14'),
(10, 1129.50,'2014-09-02'),
(11, 1290.87,'2014-10-02'),
(12, 1460.16,'2014-11-05'),
(13, 1626.44,'2014-12-01'),
(14, 1818.18,'2014-12-31');

---------------------------------------------

KONIEC ZADANIA0
*/

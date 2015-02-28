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
    private $electroManager;

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

        $form = $this->createform(new DateRangeType());        
        $form->submit($request->query->all());
  
        if($form->isValid()) {
            $startDate = $form->get('startDate')->getData(); 
            $endDate = $form->get('endDate')->getData();
            
            if ( !$this->electroManager->checkRange($startDate, $endDate)){
                throw $this->createNotFoundException();  
            }
            
            $startDate = date('Y-m-d', $form->get('startDate')->getData()); 
            $endDate = date('Y-m-d', $form->get('endDate')->getData());
            
            $matchedArray = $this->electroManager->getDatesArray($startDate, $endDate);
            $averageArray = $this->electroManager->getAveragesArray($matchedArray, $startDate, $endDate);

            return new JsonResponse($averageArray, 200);
        }
        else throw $this->createNotFoundException();  
    }

    /**
     * @Route("/test")
     * @Method({"GET"})
     */
    public function testAction() {
        $startDate = '2014-01-01';
        $endDate = '2014-01-11';

        $matchedArray = $this->electroManager->getDatesArray($startDate, $endDate);
        $averageArray = $this->electroManager->getAveragesArray($matchedArray, $startDate, $endDate);

        return new JsonResponse($averageArray, 200);
    }

}
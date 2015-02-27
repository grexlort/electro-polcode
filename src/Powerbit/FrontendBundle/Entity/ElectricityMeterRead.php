<?php

namespace Powerbit\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectricityMeterRead
 *
 * @ORM\Table(name="electricity_meter_reads")
 * @ORM\Entity(repositoryClass="Powerbit\FrontendBundle\Repository\ElectricityMeterReadRepository")
 */
class ElectricityMeterRead {

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="READ", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $read;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE", type="date", nullable=false)
     */
    private $date;
    
    public function toArray() {
        return [
            'id' => $this->id,
            'read' => $this->read,
            'date' => $this->date
        ];
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set read
     *
     * @param string $read
     * @return ElectricityMeterRead
     */
    public function setRead($read) {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return string 
     */
    public function getRead() {
        return $this->read;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ElectricityMeterRead
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

}

<?php

namespace AppBundle\Entity;

/**
 * operation_t
 */
class operation_t
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $siteF;

    /**
     * @var string
     */
    private $technoF;

    /**
     * @var \DateTime
     */
    private $dateF;

    /**
     * @var bool
     */
    private $flag4gF;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set siteF
     *
     * @param integer $siteF
     *
     * @return operation_t
     */
    public function setSiteF($siteF)
    {
        $this->siteF = $siteF;

        return $this;
    }

    /**
     * Get siteF
     *
     * @return int
     */
    public function getSiteF()
    {
        return $this->siteF;
    }

    /**
     * Set technoF
     *
     * @param string $technoF
     *
     * @return operation_t
     */
    public function setTechnoF($technoF)
    {
        $this->technoF = $technoF;

        return $this;
    }

    /**
     * Get technoF
     *
     * @return string
     */
    public function getTechnoF()
    {
        return $this->technoF;
    }

    /**
     * Set dateF
     *
     * @param \DateTime $dateF
     *
     * @return operation_t
     */
    public function setDateF($dateF)
    {
        $this->dateF = $dateF;

        return $this;
    }

    /**
     * Get dateF
     *
     * @return \DateTime
     */
    public function getDateF()
    {
        return $this->dateF;
    }

    /**
     * Set flag4gF
     *
     * @param boolean $flag4gF
     *
     * @return operation_t
     */
    public function setFlag4gF($flag4gF)
    {
        $this->flag4gF = $flag4gF;

        return $this;
    }

    /**
     * Get flag4gF
     *
     * @return bool
     */
    public function getFlag4gF()
    {
        return $this->flag4gF;
    }
}


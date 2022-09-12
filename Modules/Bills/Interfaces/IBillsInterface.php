<?php


namespace Modules\Bills\Interfaces;


interface IBillsInterface
{
    /**
     * Get all services available
     * @return mixed
     */
    public function getServices();


    /**
     * Get services by category
     * @return mixed
     */
    public function getServiceByCategory();


    /**
     * Get all airtime service providers
     * @return mixed
     */
    public function getAirtimeServices();


    /**
     * Get all databundle service providers
     * @return mixed
     */
    public function getDataService();


    /**
     * Get al electricity service providers
     * @return mixed
     */
    public function getElectricityService();


    /**
     * Get all epin service providers
     * @return mixed
     */
    public function getEpinService();


    /**
     * Get all epin service providers
     * @return mixed
     */
    public function getCableService();

}

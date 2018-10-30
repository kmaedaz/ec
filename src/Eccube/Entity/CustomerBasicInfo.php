<?php

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerBasicInfo
 */
class CustomerBasicInfo extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $customer_number;

    /**
     * @var integer
     */
    private $customer_pin_code;

    /**
     * @var string
     */
    private $job;

    /**
     * @var \DateTime
     */
    private $create_date;

    /**
     * @var \DateTime
     */
    private $update_date;

    /**
     * @var \Eccube\Entity\Customer
     */
    private $Customer;

    /**
     * @var \Eccube\Entity\Master\CustomerBasicInfoStatus
     */
    private $Status;

    /**
     * @var \Eccube\Entity\Master\InstructorType
     */
    private $InstructorType;

    /**
     * @var \Eccube\Entity\Master\SupporterType
     */
    private $SupporterType;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer_number
     *
     * @param string $customerNumber
     * @return CustomerBasicInfo
     */
    public function setCustomerNumber($customerNumber)
    {
        $this->customer_number = $customerNumber;

        return $this;
    }

    /**
     * Get customer_number
     *
     * @return string 
     */
    public function getCustomerNumber()
    {
        return $this->customer_number;
    }

    /**
     * Set customer_pin_code
     *
     * @param integer $customerPinCode
     * @return CustomerBasicInfo
     */
    public function setCustomerPinCode($customerPinCode)
    {
        $this->customer_pin_code = $customerPinCode;

        return $this;
    }

    /**
     * Get customer_pin_code
     *
     * @return integer 
     */
    public function getCustomerPinCode()
    {
        return $this->customer_pin_code;
    }

    /**
     * Set job
     *
     * @param string $job
     * @return CustomerBasicInfo
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return CustomerBasicInfo
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return CustomerBasicInfo
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set Customer
     *
     * @param \Eccube\Entity\Customer $customer
     * @return CustomerBasicInfo
     */
    public function setCustomer(\Eccube\Entity\Customer $customer = null)
    {
        $this->Customer = $customer;

        return $this;
    }

    /**
     * Get Customer
     *
     * @return \Eccube\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->Customer;
    }

    /**
     * Set Status
     *
     * @param \Eccube\Entity\Master\CustomerBasicInfoStatus $status
     * @return CustomerBasicInfo
     */
    public function setStatus(\Eccube\Entity\Master\CustomerBasicInfoStatus $status = null)
    {
        $this->Status = $status;

        return $this;
    }

    /**
     * Get Status
     *
     * @return \Eccube\Entity\Master\CustomerBasicInfoStatus 
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Set InstructorType
     *
     * @param \Eccube\Entity\Master\InstructorType $instructorType
     * @return CustomerBasicInfo
     */
    public function setInstructorType(\Eccube\Entity\Master\InstructorType $instructorType = null)
    {
        $this->InstructorType = $instructorType;

        return $this;
    }

    /**
     * Get InstructorType
     *
     * @return \Eccube\Entity\Master\InstructorType 
     */
    public function getInstructorType()
    {
        return $this->InstructorType;
    }

    /**
     * Set SupporterType
     *
     * @param \Eccube\Entity\Master\SupporterType $supporterType
     * @return CustomerBasicInfo
     */
    public function setSupporterType(\Eccube\Entity\Master\SupporterType $supporterType = null)
    {
        $this->SupporterType = $supporterType;

        return $this;
    }

    /**
     * Get SupporterType
     *
     * @return \Eccube\Entity\Master\SupporterType 
     */
    public function getSupporterType()
    {
        return $this->SupporterType;
    }
}

<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Coupon\Model;

use Magento\Framework\DataObject;
use AlbertMage\Coupon\Api\Data\CouponInterface;

/**
 * Class Coupon
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class Coupon extends DataObject implements CouponInterface
{

    /**
     * Get couponId
     *
     * @return int|null
     */
    public function getCouponId()
    {
        return $this->getData(self::COUPON_ID);
    }

    /**
     * Set couponId
     *
     * @param int $couponId
     * @return $this
     */
    public function setCouponId($couponId)
    {
        return $this->setData(self::COUPON_ID, $couponId);
    }

    /**
     * Get code
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get label
     *
     * @return string|null
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get discount amount
     *
     * @return float|null
     */
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * Set discount amount
     *
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount($discountAmount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discountAmount);
    }

    /**
     * Get simple action.
     *
     * @return string|null.
     */
    public function getSimpleAction()
    {
        return $this->getData(self::SIMPLE_ACTION);
    }

    /**
     * Sets simple action.
     *
     * @param string $simpleAction
     * @return $this
     */
    public function setSimpleAction($simpleAction)
    {
        return $this->setData(self::SIMPLE_ACTION, $simpleAction);
    }

    /**
     * Get available
     *
     * @return int|null
     */
    public function getAvailable()
    {
        return $this->getData(self::AVAILABLE);
    }

    /**
     * Set available
     *
     * @param int $available
     * @return $this
     */
    public function setAvailable($available)
    {
        return $this->setData(self::AVAILABLE, $available);
    }

    /**
     * Get qty
     *
     * @return int|null
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * Set qty
     *
     * @param int $qty
     * @return $this
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Get timesUsed
     *
     * @return int|null
     */
    public function getTimesUsed()
    {
        return $this->getData(self::TIMES_USED);
    }

    /**
     * Set timesUsed
     *
     * @param int $timesUsed
     * @return $this
     */
    public function setTimesUsed($timesUsed)
    {
        return $this->setData(self::TIMES_USED, $timesUsed);
    }

    /**
     * Gets the from-date timestamp for the coupon.
     *
     * @return string|null from-date timestamp.
     */
    public function getFromDate()
    {
        return $this->getData(self::FROM_DATE);
    }

    /**
     * Sets the from-date timestamp for the coupon.
     *
     * @param string $fromDate timestamp
     * @return $this
     */
    public function setFromDate($fromDate)
    {
        return $this->setData(self::FROM_DATE, $fromDate);
    }

    /**
     * Gets the expiration-date timestamp for the coupon.
     *
     * @return string|null expiration-date timestamp.
     */
    public function getExpirationDate()
    {
        return $this->getData(self::EXPIRATION_DATE);
    }

    /**
     * Sets the expiration-date timestamp for the coupon.
     *
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        return $this->setData(self::EXPIRATION_DATE, $expirationDate);
    }
}

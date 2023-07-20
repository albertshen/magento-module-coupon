<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Coupon\Api\Data;

/**
 * Coupon Interface
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface CouponInterface
{
    const COUPON_ID = 'coupon_id';

    const CODE = 'code';

    const NAME = 'name';

    const LABEL = 'label';

    const DESCRIPTION = 'description';

    const DISCOUNT_AMOUNT = 'discount_amount';

    const SIMPLE_ACTION = 'simple_action';

    const AVAILABLE = 'available';

    const QTY = 'qty';

    const TIMES_USED = 'times_used';

    const FROM_DATE = 'from_date';

    const EXPIRATION_DATE = 'expiration_date';

    /**
     * Get CouponId
     *
     * @return int|null
     */
    public function getCouponId();

    /**
     * Set CouponId
     *
     * @param int $couponId
     * @return $this
     */
    public function setCouponId($couponId);

    /**
     * Get code
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label);


    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get discount amount
     *
     * @return float|null
     */
    public function getDiscountAmount();

    /**
     * Set discount amount
     *
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get simple action.
     *
     * @return string|null.
     */
    public function getSimpleAction();

    /**
     * Sets simple action.
     *
     * @param string $simpleAction
     * @return $this
     */
    public function setSimpleAction($simpleAction);

    /**
     * Get available
     *
     * @return int|null
     */
    public function getAvailable();

    /**
     * Set available
     *
     * @param int $available
     * @return $this
     */
    public function setAvailable($available);

    /**
     * Get qty
     *
     * @return int|null
     */
    public function getQty();

    /**
     * Set qty
     *
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Get timesUsed
     *
     * @return int|null
     */
    public function getTimesUsed();

    /**
     * Set timesUsed
     *
     * @param int $timesUsed
     * @return $this
     */
    public function setTimesUsed($timesUsed);

    /**
     * Gets the from-date timestamp for the coupon.
     *
     * @return string|null from-date timestamp.
     */
    public function getFromDate();

    /**
     * Sets the from-date timestamp for the coupon.
     *
     * @param string $fromDate timestamp
     * @return $this
     */
    public function setFromDate($fromDate);

    /**
     * Gets the expiration-date timestamp for the coupon.
     *
     * @return string|null expiration-date timestamp.
     */
    public function getExpirationDate();

    /**
     * Sets the expiration-date timestamp for the coupon.
     *
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate($expirationDate);
}

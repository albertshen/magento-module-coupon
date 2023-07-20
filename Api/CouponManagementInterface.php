<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Coupon\Api;

/**
 * Interface for CouponManagement.
 * @api
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface CouponManagementInterface
{

    /**
     * Get coupon list
     *
     * @param int $customerId
     * @param string $status
     * @param int $page
     * @param int $pageSize
     * @return \AlbertMage\Coupon\Api\Data\CouponSearchResultsInterface
     */
    public function getList($customerId, $status = 'notused', $page = 1, $pageSize = 10);

    /**
     * Get applied coupon list
     *
     * @param int $cartId
     * @return \AlbertMage\Coupon\Api\Data\CouponInterface[]
     */
    public function getAppliedCoupons($cartId);

    /**
     * Adds a coupon by code to a specified cart.
     *
     * @param int $cartId The cart ID.
     * @param string $couponCode The coupon code data.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     * @throws \Magento\Framework\Exception\CouldNotSaveException The specified coupon could not be added.
     */
    public function setCoupon($cartId, $couponCode);

    /**
     * Deletes a coupon from a specified cart.
     *
     * @param int $cartId The cart ID.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     * @throws \Magento\Framework\Exception\CouldNotDeleteException The specified coupon could not be deleted.
     */
    public function removeCoupon($cartId);

}
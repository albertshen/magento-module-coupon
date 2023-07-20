<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Coupon\Model;

use AlbertMage\Coupon\Api\Data\CouponInterface;
use AlbertMage\Coupon\Api\Data\CouponInterfaceFactory;
use AlbertMage\Coupon\Api\Data\CouponSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\SalesRule\Model\ResourceModel\Coupon\Collection as CouponCollection;
use Magento\SalesRule\Model\RuleFactory;
use Magento\SalesRule\Model\RulesApplier;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Api\CouponManagementInterface;
use Magento\SalesRule\Model\CouponFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Math\RandomFactory;
use Magento\SalesRule\Model\Validator;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CouponManagement
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class CouponManagement implements \AlbertMage\Coupon\Api\CouponManagementInterface
{

    /**
     * @var CouponInterfaceFactory
     */
    protected $couponInterfaceFactory;

    /**
     * @var CouponSearchResultsInterfaceFactory
     */
    protected $couponSearchResultsFactory;

    /**
     * @var SearchCriteriaFactory
     */
    protected $searchCriteriaFactory;

    /**
     * @var CouponCollection
     */
    protected $couponCollection;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var RulesApplier
     */
    protected $rulesApplier;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var CouponManagementInterface
     */
    protected $couponManagementInterface;
 
     /**
     * @var CouponFactory
     */
    protected $couponFactory;

    /**
     * @var RandomFactory
     */
    private $randomFactory;

    /**
     * @var Validator
     */
    private $validator;
    
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param CouponInterfaceFactory $couponInterfaceFactory
     * @param CouponSearchResultsInterfaceFactory $couponSearchResultsFactory
     * @param SearchCriteriaFactory $searchCriteriaFactory
     * @param CouponCollection $couponCollection
     * @param ResourceConnection $resource
     * @param RuleFactory $ruleFactory
     * @param RulesApplier $rulesApplier
     * @param QuoteFactory $quoteFactory
     * @param CouponManagementInterface $couponManagementInterface
     * @param CouponFactory $couponFactory
     * @param RandomFactory $randomFactory
     * @param Validator $validator
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CouponInterfaceFactory $couponInterfaceFactory,
        CouponSearchResultsInterfaceFactory $couponSearchResultsFactory,
        SearchCriteriaFactory $searchCriteriaFactory,
        CouponCollection $couponCollection,
        ResourceConnection $resource,
        RuleFactory $ruleFactory,
        RulesApplier $rulesApplier,
        QuoteFactory $quoteFactory,
        CouponManagementInterface $couponManagementInterface,
        CouponFactory $couponFactory,
        RandomFactory $randomFactory,
        Validator $validator,
        StoreManagerInterface $storeManager
    ) {
        $this->couponInterfaceFactory = $couponInterfaceFactory;
        $this->couponSearchResultsFactory = $couponSearchResultsFactory;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->couponCollection = $couponCollection;
        $this->resource = $resource;
        $this->ruleFactory = $ruleFactory;
        $this->rulesApplier = $rulesApplier;
        $this->quoteFactory = $quoteFactory;
        $this->couponManagementInterface = $couponManagementInterface;
        $this->couponFactory = $couponFactory;
        $this->randomFactory = $randomFactory;
        $this->validator = $validator;
        $this->storeManager = $storeManager;
    }

    /**
     * Get coupon list
     *
     * @param int $customerId
     * @param string $status
     * @param int $page
     * @param int $pageSize
     * @return \AlbertMage\Coupon\Api\Data\CouponSearchResultsInterface
     */
    public function getList($customerId, $status = 'notused', $page = 1, $pageSize = 10)
    {
        $this->couponCollection->addFieldToFilter('customer_id', $customerId);

        if ($status == 'used') {
            $this->couponCollection->addFieldToFilter('times_used', ['gt' => 0]);
        }
        if ($status == 'notused') {
            $now = new \DateTime();
            $this->couponCollection->addFieldToFilter('expiration_date', [['gteq' => $now->format('Y-m-d 00:00:00')], ['null' => true]]);
            $this->couponCollection->addFieldToFilter('usage_limit', [['neq' => new \Zend_Db_Expr('times_used')], ['eq' => 0]]);
        }
        if ($status == 'expired') {
            $now = new \DateTime();
            $this->couponCollection->addFieldToFilter('expiration_date', ['lteq' => $now->format('Y-m-d H:i:s')]);
        }

        // $this->couponCollection->getSelect()->joinLeft(
        //     ['rule' => $this->resource->getTableName('salesrule')],
        //     'main_table.rule_id = rule.rule_id',
        //     [
        //         'rule.name',
        //         'rule.description',
        //         'rule.simple_action',
        //         'rule.discount_amount',
        //         'rule.from_date'
        //     ]
        // );

        $this->couponCollection->setOrder('created_at','DESC');
        $this->couponCollection->setCurPage($page);
        $this->couponCollection->setPageSize($pageSize);
        $newCoupons = [];
        foreach ($this->couponCollection->getItems() as $item) {
            $newCoupons[] = $this->getCouponItem($item);
        }
        // Set search criteria
        $searchCriteria = $this->searchCriteriaFactory->create();
        $searchCriteria->setPageSize($this->couponCollection->getPageSize());
        $searchCriteria->setCurrentPage($this->couponCollection->getCurPage());

        $orderSearchResults = $this->couponSearchResultsFactory->create();
        $orderSearchResults->setItems($newCoupons);
        $orderSearchResults->setSearchCriteria($searchCriteria);
        $orderSearchResults->setTotalCount($this->couponCollection->getSize());

        return $orderSearchResults;
    }

    /**
     * Get applied coupon list
     *
     * @param int $cartId
     * @return \AlbertMage\Coupon\Api\Data\CouponInterface[]
     */
    public function getAppliedCoupons($cartId)
    {
        $quote = $this->quoteFactory->create()->load($cartId);
        $this->couponCollection->addFieldToFilter('customer_id', $quote->getCustomerId());
        $now = new \DateTime();
        $this->couponCollection->addFieldToFilter('expiration_date', [['gteq' => $now->format('Y-m-d 00:00:00')], ['null' => true]]);
        $this->couponCollection->addFieldToFilter('usage_limit', [['neq' => new \Zend_Db_Expr('times_used')], ['eq' => 0]]);
        $this->couponCollection->setOrder('created_at','DESC');

        $newCoupons = [];
        $store = $this->storeManager->getStore($quote->getStoreId());
        $items = $quote->getAllItems();
        foreach ($this->couponCollection->getItems() as $coupon) {
            $this->validator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $coupon->getCode());
            $this->validator->initTotals($items, $quote->getShippingAddress());
            $rules = $this->validator->getRules($quote->getShippingAddress());
            foreach ($rules as $rule) {
                /** @var Item $item */
                foreach ($items as $item) {
                    if ($quote->getIsMultiShipping() && $item->getAddress()->getId() !== $address->getId()) {
                        continue;
                    }
                    if ($item->getNoDiscount() || !$this->validator->canApplyDiscount($item) || $item->getParentItem()) {
                        continue;
                    }
                    $this->validator->process($item, $rule);
                }
                $appliedRuleIds = $quote->getAppliedRuleIds() ? explode(',', $quote->getAppliedRuleIds()) : [];
            }
            if (in_array($coupon->getRuleId(), $appliedRuleIds)) {
                $newCoupon = $this->getCouponItem($coupon);
                $newCoupons[] = $this->getCouponItem($coupon);
            }
        }
        return $newCoupons;
    }

    /**
     * Get coupon item from system coupon
     *
     * @param \Magento\SalesRule\Api\Data\CouponInterface $coupon
     * @return \AlbertMage\Coupon\Api\Data\CouponInterface
     */
    public function getCouponItem(\Magento\SalesRule\Api\Data\CouponInterface $coupon)
    {
        $rule = $this->ruleFactory->create()->load($coupon->getRuleId());
        $newCoupon = $this->couponInterfaceFactory->create(['data' => $coupon->toArray()]);
        $newCoupon->setName($rule->getName());
        $newCoupon->setLabel($rule->getStoreLabel());
        $newCoupon->setDescription($rule->getDescription());

        $newCoupon->setQty($coupon->getUsageLimit() - $coupon->getTimesUsed());
        if ($coupon->getUsageLimit() == 0) {
            $newCoupon->setQty(1);
        }
        $newCoupon->setTimesUsed($coupon->getTimesUsed());

        if ($coupon->getCreatedAt() > $rule->getFromDate()) {
            $newCoupon->setFromDate(date('Y.m.d', strtotime($coupon->getCreatedAt())));
        } else {
            $newCoupon->setFromDate(date('Y.m.d', strtotime($rule->getFromDate())));
        }

        if (($coupon->getTimesUsed() > 0 && $coupon->getTimesUsed() == $coupon->getUsageLimit()) || date('Y.m.d') < $newCoupon->getFromDate()) {
            $newCoupon->setAvailable(0);
        } else {
            $newCoupon->setAvailable(1);
        }
        if ($coupon->getExpirationDate()) {
            $newCoupon->setExpirationDate(date('Y.m.d', strtotime($coupon->getExpirationDate())));
        }

        $newCoupon->setSimpleAction($rule->getSimpleAction());

        $newCoupon->setDiscountAmount($rule->getDiscountAmount());

        return $newCoupon;

    }

    /**
     * Adds a coupon by code to a specified cart.
     *
     * @param int $cartId The cart ID.
     * @param string $couponCode The coupon code data.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     * @throws \Magento\Framework\Exception\CouldNotSaveException The specified coupon could not be added.
     */
    public function setCoupon($cartId, $couponCode)
    {
        try {
            $this->couponManagementInterface->set($cartId, $couponCode);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The coupon code couldn\'t be applied: ' .$e->getMessage()), $e, 40200);
        }
        return true;
        
    }

    /**
     * Deletes a coupon from a specified cart.
     *
     * @param int $cartId The cart ID.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     * @throws \Magento\Framework\Exception\CouldNotDeleteException The specified coupon could not be deleted.
     */
    public function removeCoupon($cartId)
    {
        try {
            $this->couponManagementInterface->remove($cartId);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The coupon code couldn\'t be deleted. Verify the coupon code and try again.'), $e, 40202);
        }
        return true;
    }

    /**
     * Generate coupon code for customer.
     *
     * @param int $customerId.
     * @param int $ruleId.
     * @param int $qty.
     * @return string
     */
    public function generateCode($customerId, $ruleId, $toDate = '', $qty = 1)
    {
        $rule = $this->ruleFactory->create()->load($ruleId);
        if ($rule->getIsActive() && ($rule->getToDate() >= date('Y-m-d') || !$rule->getToDate())) {
            $mathRandom = $this->randomFactory->create();
            $coupon = $this->couponFactory->create();
            $coupon->setRuleId($rule->getRuleId());
            $coupon->setCode($mathRandom->getUniqueHash());
            $coupon->setUsageLimit($qty);
            $coupon->setUsagePerCustomer(0);
            $coupon->setTimesUsed(0);
            $coupon->setType(1);
            $coupon->setCustomerId($customerId);
            $coupon->setCreatedAt(date('Y-m-d H:i:s'));
            if ($toDate) {
                $coupon->setExpirationDate($toDate);
            } else {
                if ($rule->getToDate()) {
                    $toDate = $rule->getToDate();
                }
                if ($rule->getDays()) {
                    $toDate = date('Y-m-d', strtotime("+".$rule->getDays()." day"));
                }
                $coupon->setExpirationDate($toDate);
            }
            $coupon->save();
            return $coupon->getCode();
        }
        return null;
    }
}
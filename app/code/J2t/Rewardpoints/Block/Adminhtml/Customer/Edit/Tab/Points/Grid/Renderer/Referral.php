<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace J2t\Rewardpoints\Block\Adminhtml\Customer\Edit\Tab\Points\Grid\Renderer;

/**
 * Adminhtml newsletter queue grid block status item renderer
 */
class Referral extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_referralModel = null;
	public function __construct(
        \Magento\Backend\Block\Context $context,
        \J2t\Rewardpoints\Model\Referral $referral,
        array $data = []
    ) {
        $this->_referralModel = $referral;
        parent::__construct($context, $data);
    }

    /**
     * Constructor for Grid Renderer Status
     *
     * @return void
     */
    /*protected function _construct()
    {
        self::$_statuses = [
            \Magento\Newsletter\Model\Queue::STATUS_SENT => __('Sent'),
            \Magento\Newsletter\Model\Queue::STATUS_CANCEL => __('Cancel'),
            \Magento\Newsletter\Model\Queue::STATUS_NEVER => __('Not Sent'),
            \Magento\Newsletter\Model\Queue::STATUS_SENDING => __('Sending'),
            \Magento\Newsletter\Model\Queue::STATUS_PAUSE => __('Paused'),
        ];
        parent::_construct();
    }*/

    /**
     * @param \Magento\Framework\Object $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
		$referralId = $row->getData($this->getColumn()->getIndex());
        if($referralId){
            //$customer = Mage::getModel('rewardpoints/referral')->load($referralId);
			//$this->_objectManager->create('J2t\Referral\Model\Referral');
			$customer = $this->_referralModel->load($referralId);
            if ($customer->getRewardpointsReferralChildId()){
                return "#{$customer->getRewardpointsReferralChildId()} ". $customer->getRewardpointsReferralName() . " ({$customer->getRewardpointsReferralEmail()})";
            }
        }
        return '-';
    }
}

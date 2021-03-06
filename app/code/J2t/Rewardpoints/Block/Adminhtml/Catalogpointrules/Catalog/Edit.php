<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace J2t\Rewardpoints\Block\Adminhtml\Catalogpointrules\Catalog;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
        
        $this->_formScripts[] = "
            checkTypes = function(){
                if (jQuery('#rule_action_type').val() == '". \J2t\Rewardpoints\Model\Catalogpointrule::RULE_ACTION_TYPE_DONTPROCESS."'){
                    jQuery('#rule_points').val('1');
                    jQuery('#rule_points').closest('.field').hide();
                } else {
                    jQuery('#rule_points').closest('.field').show();
                }
            };
            
            jQuery(document).ready(function() {
            
                jQuery('#rule_action_type').change(function() {
                    checkTypes();
                });

                checkTypes();
            });
            
        ";
        
        
    }

    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Apply" button
     * Add "Save and Continue" button
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'J2t_Rewardpoints';
        $this->_controller = 'adminhtml_catalogpointrules_catalog';

        parent::_construct();

        /*$this->buttonList->add(
            'save_apply',
            [
                'class' => 'save',
                'label' => __('Save and Apply'),
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'save',
                            'target' => '#edit_form',
                            'eventData' => ['action' => ['args' => ['auto_apply' => 1]]],
                        ],
                    ],
                ]
            ]
        );*/

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );
    }

    /**
     * Getter for form header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $rule = $this->_coreRegistry->registry('current_point_catalog_rule');
        if ($rule->getRuleId()) {
            return __("Edit Rule '%1'", $this->escapeHtml($rule->getName()));
        } else {
            return __('New Rule');
        }
    }
}

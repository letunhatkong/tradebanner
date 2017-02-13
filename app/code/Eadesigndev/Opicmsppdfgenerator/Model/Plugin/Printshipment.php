<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Opicmsppdfgenerator\Model\Plugin;

use Eadesigndev\Opicmsppdfgenerator\Helper\Data;

class Printshipment
{

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $urlInterface;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * Printinvoice constructor.
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\UrlInterface $urlInterface
     * @param Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\UrlInterface $urlInterface,
        Data $dataHelper
    )
    {
        $this->coreRegistry = $coreRegistry;
        $this->urlInterface = $urlInterface;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @return mixed
     */
    public function getShipment()
    {
        return $this->coreRegistry->registry('current_shipment');
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetPrintUrl($subject, $result)
    {
        if (!$this->dataHelper->isEnable(\Eadesigndev\Opicmsppdfgenerator\Helper\Data::ENABLE_SHIPMENT)) {
            return $result;
        }

        $lastItem = $this->dataHelper->getTemplateStatus(
            $this->getShipment(),
            \Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType::TYPE_SHIPMENT);

        if (empty($lastItem->getId())) {
            return $result;
        }

        return $this->_print($lastItem);
    }

    /**
     * @param $lastItem
     * @return string
     */
    protected function _print($lastItem)
    {
        return $this->urlInterface->getUrl(
            'opicmsppdfgenerator/*/printpdf',
            [
                'template_id' => $lastItem->getId(),
                'order_id' => $this->getShipment()->getOrder()->getId(),
                'shipment_id' => $this->getShipment()->getId()
            ]);
    }

}

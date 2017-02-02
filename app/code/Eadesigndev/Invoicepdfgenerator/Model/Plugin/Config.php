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

namespace Eadesigndev\Invoicepdfgenerator\Model\Plugin;

class Config
{

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $url;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Config constructor.
     * @param \Magento\Backend\Model\UrlInterface $url
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Model\UrlInterface $url,
        \Magento\Framework\Registry $registry
    )
    {
        $this->url = $url;
        $this->registry = $registry;
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetVariablesWysiwygActionUrl($subject, $result)
    {
        if ($this->registry->registry('pdfgenerator_template')) {
            return $this->getUrl();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url->getUrl('invoicepdfgenerator/variable/template');
    }

}

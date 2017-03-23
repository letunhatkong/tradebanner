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

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Invoice;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Abstractpdf;
use Eadesigndev\Pdfgenerator\Helper\Pdf;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Printpdf extends Abstractpdf
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::sales_invoice';

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */

    private $fileFactory;
    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */

    private $resultForwardFactory;

    /**
     * @var Pdf
     */
    private $helper;

    /**
     * Printpdf constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Email\Model\Template\Config $emailConfig
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Pdf $helper
     * @param DateTime $dateTime
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Email\Model\Template\Config $emailConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        Pdf $helper,
        DateTime $dateTime,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

    )
    {
        $this->fileFactory = $fileFactory;
        $this->helper = $helper;
        parent::__construct($context, $coreRegistry, $emailConfig, $resultJsonFactory);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->dateTime = $dateTime;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {

        $templateId = $this->getRequest()->getParam('template_id');
        if (!$templateId) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $templateModel = $this->_objectManager
            ->create('Eadesigndev\Pdfgenerator\Api\TemplatesRepositoryInterface')
            ->getById($templateId);

        if (!$templateModel) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $invoiceId = $this->getRequest()->getParam('invoice_id');
        if (!$invoiceId) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $invoice = $this->_objectManager
            ->create('Magento\Sales\Api\InvoiceRepositoryInterface')
            ->get($invoiceId);
        if (!$invoice) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $helper = $this->helper;

        $helper->setInvoice($invoice);
        $helper->setTemplate($templateModel);

        $pdfFileData = $helper->template2Pdf();

        $date = $this->dateTime->date('Y-m-d_H-i-s');

        $fileName = $pdfFileData['filename'] . $date . '.pdf';

        return $this->fileFactory->create(
            $fileName,
            $pdfFileData['filestream'],
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }
}
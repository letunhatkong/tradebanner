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

namespace Eadesigndev\Pdfgenerator\Model;

use \Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterface;
use \Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterfaceFactory;
use \Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator as TemplateResource;
use \Eadesigndev\Pdfgenerator\Api\TemplatesRepositoryInterface;

class PdfgeneratorRepository implements TemplatesRepositoryInterface
{

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var TemplateResource
     */
    private $resource;

    /**
     * @var TemplatesInterface
     */
    private $templatesInterface;

    /**
     * @var TemplatesInterfaceFactory
     */
    private $templatesInterfaceFactory;

    /**
     * @var \Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory
     */
    private $pdfgeneratorFactory;

    /**
     * PdfgeneratorRepository constructor.
     * @param TemplateResource $resource
     * @param TemplatesInterface $templatesInterface
     * @param TemplatesInterfaceFactory $templatesInterfaceFactory
     * @param \Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory $pdfgeneratorFactory
     */
    public function __construct(
        TemplateResource $resource,
        TemplatesInterface $templatesInterface,
        TemplatesInterfaceFactory $templatesInterfaceFactory,
        PdfgeneratorFactory $pdfgeneratorFactory
    )
    {
        $this->resource = $resource;
        $this->templatesInterface = $templatesInterface;
        $this->templatesInterfaceFactory = $templatesInterfaceFactory;
        $this->pdfgeneratorFactory = $pdfgeneratorFactory;
    }

    /**
     * @param TemplatesInterface $template
     * @return TemplatesInterface
     */
    public function save(TemplatesInterface $template)
    {
        try {
            $this->resource->save($template);
        } catch (\Exception $exception) {
        }

        return $template;
    }

    /**
     * @param \Eadesigndev\Pdfgenerator\Api\the $templateId
     * @return mixed
     */
    public function getById($templateId)
    {
        if (!isset($this->instances[$templateId])) {
            $template = $this->pdfgeneratorFactory->create();
            $this->resource->load($template, $templateId);

            if (!$template->getId()) {
            }

            $this->instances[$templateId] = $template;
        }

        return $this->instances[$templateId];
    }

    /**
     * @param TemplatesInterface $template
     * @return bool
     */
    public function delete(TemplatesInterface $template)
    {
        $id = $template->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($template);
        } catch (\Exception $e) {
        }

        unset($this->instances[$id]);

        return true;
    }

    /**
     * @param \Eadesigndev\Pdfgenerator\Api\the $templateId
     * @return bool
     */
    public function deleteById($templateId)
    {
        $template = $this->getById($templateId);
        return $this->delete($template);
    }

}
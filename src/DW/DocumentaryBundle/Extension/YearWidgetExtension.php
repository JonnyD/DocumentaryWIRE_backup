<?php

namespace DW\DocumentaryBundle\Extension;

use DW\DocumentaryBundle\Service\DocumentaryService;

class YearWidgetExtension extends \Twig_Extension
{
    /**
     * @var DocumentaryService $documentaryService
     */
    private $documentaryService;

    /**
     * @param DocumentaryService $documentaryService
     */
    public function __construct(DocumentaryService $documentaryService)
    {
        $this->documentaryService = $documentaryService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'yearWidget' => new \Twig_Function_Method($this, 'yearWidget')
        );
    }

    /**
     * @return mixed
     */
    public function yearWidget()
    {
        $years = $this->documentaryService->getYears();
        return $years;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yearWidgetExtension';
    }
}
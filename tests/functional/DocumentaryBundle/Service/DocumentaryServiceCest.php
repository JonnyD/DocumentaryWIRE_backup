<?php

namespace DW\DocumentaryBundle\Service;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\DocumentaryBundle\Entity\DocumentaryStatus;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use \FunctionalTester as Tester;
use Mockery as m;
use DW\DocumentaryBundle\Service\DocumentaryService;

class DocumentaryServiceCest
{
    /** @var DocumentaryService $documentaryService */
    private $documentaryService;

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->documentaryService = $serviceContainer->get(DocumentaryServices::DOCUMENTARY);
    }

    public function _after(Tester $I)
    {
        m::close();
    }

    public function testCreateDocumentary(Tester $I)
    {
        $title = "Test Title";
        $slug = "test-title";
        $description = "This is a test description";
        $excerpt = "Test excerpt";
        $status = DocumentaryStatus::PUBLISH;

        $documentary = $this->documentaryService->createDocumentary(
            $title, $slug, $description, $excerpt, $status);

        $I->assertNotNull($documentary);
        $I->assertEquals($title, $documentary->getTitle());
        $I->assertEquals($slug, $documentary->getSlug());
        $I->assertEquals($description, $documentary->getDescription());
        $I->assertEquals($excerpt, $documentary->getExcerpt());
        $I->assertEquals($status, $documentary->getStatus());
    }

    public function testAddDocumentary(Tester $I)
    {
        $documentary = new Documentary();
        $documentary->setTitle("Test");
        $documentary->setSlug("test-title");
        $documentary->setDescription("Description");
        $documentary->setExcerpt("excerpt");
        $documentary->setStatus(DocumentaryStatus::PUBLISH);

        $this->documentaryService->addDocumentary($documentary);
    }

    public function testGetDocumentaryById(Tester $I)
    {
        $documentary = $this->documentaryService->getDocumentaryById(99999);

        $I->assertNotNull($documentary);
    }

    public function testGetDocumentaryBySlug(Tester $I)
    {
        $slug = "test-title";
        $documentary = $this->documentaryService->getDocumentaryBySlug($slug);

        $I->assertNotNull($documentary);
    }

    public function testGetDocumentaryByTruncatedSlug(Tester $I)
    {
        $truncatedSlug = "test-tit";
        $documentary = $this->documentaryService->getDocumentaryByTruncatedSlug($truncatedSlug);

        $I->assertNotNull($documentary);
    }

    public function testGetAllDocumentaries(Tester $I)
    {
        $documentaries = $this->documentaryService->getAllDocumentaries();

        $I->assertNotNull($documentaries);
        $I->assertEquals(10, count($documentaries));
    }

    public function testGetOrderBy()
    {
        //TODO
    }

    public function testGetOrder()
    {
        //TODO
    }

    public function testGetPublishedDocumentaryKeys(Tester $I)
    {

    }

    public function testGetPublishedDocumentaryKeysByCategory()
    {

    }

    public function testGetPublishedDocumentaryKeysByYear()
    {

    }

    public function testGetFeaturedDocumentaries()
    {

    }

    public function testGetYearss()
    {

    }

    public function testGetDurations()
    {

    }

    public function testPublishedDocumentaryKeysByDuration()
    {

    }

    public function testAmountOfDocumentariesByDuration()
    {

    }

    public function testGetAmountOfDocumentariesWithNodDuration()
    {

    }

    public function testGetDocumentariesByTag()
    {

    }

    public function testGetDocumentariesByCategory()
    {

    }

    public function testGetRelatedDocumentaries()
    {

    }

    public function testGetEmbedCode()
    {

    }

    public function testGetLastUpdatedDocumentary()
    {

    }

    public function testGetLastUpdatedDocumentaryInCaegory()
    {

    }

}
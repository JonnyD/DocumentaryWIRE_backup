<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{
    public function getContainer()
    {
        return $this->getModule('Symfony2')->container;
    }
}

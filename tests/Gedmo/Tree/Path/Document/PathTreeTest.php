<?php

namespace Gedmo\Tree;

use Tool\BaseTestCaseMongoODM;
use Doctrine\Common\EventManager;
use Tree\Fixture\Path\Document\Category;

/**
 * These are tests for tree behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 *
 * @package Gedmo.Tree
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class PathTreeTest extends BaseTestCaseMongoODM
{
    const CATEGORY = 'Tree\\Fixture\\Path\\Document\\Category';

    protected function setUp()
    {
        parent::setUp();
        $evm = new EventManager();
        $evm->addEventSubscriber(new TreeListener());
        $evm->addEventSubscriber(new \Gedmo\Sluggable\SluggableListener());

        $this->getMockDocumentManager($evm);
    }

    public function testLogGeneration()
    {
        $this->populate();
        //die('test');
    }

    private function populate()
    {
        $food = new Category();
        $food->setTitle("Food");

        $sports = new Category();
        $sports->setTitle("Sports");

        $fruits = new Category();
        $fruits->setTitle("Fruits");
        $fruits->setParent($food);

        $vegies = new Category();
        $vegies->setTitle("Vegitables");
        $vegies->setParent($food);

        $carrots = new Category();
        $carrots->setTitle("Carrots");
        $carrots->setParent($vegies);

        $potatoes = new Category();
        $potatoes->setTitle("Potatoes");
        $potatoes->setParent($vegies);

        $this->dm->persist($food);
        $this->dm->persist($sports);
        $this->dm->persist($fruits);
        $this->dm->persist($vegies);
        $this->dm->persist($carrots);
        $this->dm->persist($potatoes);
        $this->dm->flush();
        $this->dm->clear();
    }
}

<?php

namespace Tree\Fixture\Path\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="categories")
 * @Gedmo\Tree(type="path")
 */
class Category
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @Gedmo\Sluggable(slugField="path")
     * @ODM\String
     */
    private $title;

    /**
     * @Gedmo\TreePath
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, separator="-", updatable=true)
     * @ODM\String
     */
    private $path;

    /**
     * @Gedmo\TreeParent
     * @ODM\ReferenceOne(targetDocument="Category")
     */
    private $parent;

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
}

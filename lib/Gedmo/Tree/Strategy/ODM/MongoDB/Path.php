<?php

namespace Gedmo\Tree\Strategy\ODM\MongoDB;

use Gedmo\Tool\Wrapper\AbstractWrapper;
use Gedmo\Tree\Strategy;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Tree\TreeListener;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * This strategy makes tree act like
 * nested set.
 *
 * This behavior can inpact the performance of your application
 * since nested set trees are slow on inserts and updates.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo.Tree.Strategy.ORM
 * @subpackage Nested
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Path implements Strategy
{
    /**
     * Previous sibling position
     */
    const PREV_SIBLING = 'PrevSibling';

    /**
     * Next sibling position
     */
    const NEXT_SIBLING = 'NextSibling';

    /**
     * Last child position
     */
    const LAST_CHILD = 'LastChild';

    /**
     * First child position
     */
    const FIRST_CHILD = 'FirstChild';

    /**
     * TreeListener
     *
     * @var AbstractTreeListener
     */
    protected $listener = null;

    /**
     * Stores a list of node position strategies
     * for each node by object hash
     *
     * @var array
     */
    private $nodePositions = array();

    /**
     * {@inheritdoc}
     */
    public function __construct(TreeListener $listener)
    {
        $this->listener = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Strategy::PATH;
    }

    /**
     * Set node position strategy
     *
     * @param string $oid
     * @param string $position
     */
    public function setNodePosition($oid, $position)
    {
        $valid = array(
            self::FIRST_CHILD,
            self::LAST_CHILD,
            self::NEXT_SIBLING,
            self::PREV_SIBLING
        );
        if (!in_array($position, $valid, false)) {
            throw new \Gedmo\Exception\InvalidArgumentException("Position: {$position} is not valid in nested set tree");
        }
        $this->nodePositions[$oid] = $position;
    }

    /**
     * {@inheritdoc}
     */
    public function processMetadataLoad($dm, $meta)
    {
        $config = $this->listener->getConfiguration($dm, $meta->name);
        //var_dump($config);
        //die('triggered');
    }

    /**
     * {@inheritdoc}
     */
    public function processScheduledInsertion($em, $node)
    {
        /*$meta = $em->getClassMetadata(get_class($node));
        $config = $this->listener->getConfiguration($em, $meta->name);

        $meta->getReflectionProperty($config['left'])->setValue($node, 0);
        $meta->getReflectionProperty($config['right'])->setValue($node, 0);
        if (isset($config['level'])) {
            $meta->getReflectionProperty($config['level'])->setValue($node, 0);
        }
        if (isset($config['root'])) {
            $meta->getReflectionProperty($config['root'])->setValue($node, 0);
        }*/
    }

    /**
     * {@inheritdoc}
     */
    public function processScheduledUpdate($em, $node)
    {
        /*$meta = $em->getClassMetadata(get_class($node));
        $config = $this->listener->getConfiguration($em, $meta->name);
        $uow = $em->getUnitOfWork();

        $changeSet = $uow->getEntityChangeSet($node);
        if (isset($config['root']) && isset($changeSet[$config['root']])) {
            throw new \Gedmo\Exception\UnexpectedValueException("Root cannot be changed manualy, change parent instead");
        }

        $oid = spl_object_hash($node);
        if (isset($changeSet[$config['left']]) && isset($this->nodePositions[$oid])) {
            $wrapped = AbstractWrapper::wrapp($node, $em);
            $parent = $wrapped->getPropertyValue($config['parent']);
            // revert simulated changeset
            $uow->clearEntityChangeSet($oid);
            $wrapped->setPropertyValue($config['left'], $changeSet[$config['left']][0]);
            $uow->setOriginalEntityProperty($oid, $config['left'], $changeSet[$config['left']][0]);
            // set back all other changes
            foreach ($changeSet as $field => $set) {
                if ($field !== $config['left']) {
                    $uow->setOriginalEntityProperty($oid, $field, $set[0]);
                    $wrapped->setPropertyValue($field, $set[1]);
                }
            }
            $uow->recomputeSingleEntityChangeSet($meta, $node);
            $this->updateNode($em, $node, $parent);
        } elseif (isset($changeSet[$config['parent']])) {
            $this->updateNode($em, $node, $changeSet[$config['parent']][1]);
        }*/
    }

    /**
     * {@inheritdoc}
     */
    public function processPostPersist($em, $node)
    {
        /*$meta = $em->getClassMetadata(get_class($node));
        $config = $this->listener->getConfiguration($em, $meta->name);
        $parent = $meta->getReflectionProperty($config['parent'])->getValue($node);
        $this->updateNode($em, $node, $parent, self::LAST_CHILD);*/
    }

    /**
     * {@inheritdoc}
     */
    public function processScheduledDelete($em, $node)
    {
        /*$meta = $em->getClassMetadata(get_class($node));
        $config = $this->listener->getConfiguration($em, $meta->name);
        $uow = $em->getUnitOfWork();

        $wrapped = AbstractWrapper::wrapp($node, $em);
        $leftValue = $wrapped->getPropertyValue($config['left']);
        $rightValue = $wrapped->getPropertyValue($config['right']);

        if (!$leftValue || !$rightValue) {
            return;
        }
        $rootId = isset($config['root']) ? $wrapped->getPropertyValue($config['root']) : null;
        $diff = $rightValue - $leftValue + 1;
        if ($diff > 2) {
            $dql = "SELECT node FROM {$config['useObjectClass']} node";
            $dql .= " WHERE node.{$config['left']} BETWEEN :left AND :right";
            if (isset($config['root'])) {
                $dql .= " AND node.{$config['root']} = {$rootId}";
            }
            $q = $em->createQuery($dql);
            // get nodes for deletion
            $q->setParameter('left', $leftValue + 1);
            $q->setParameter('right', $rightValue - 1);
            $nodes = $q->getResult();
            foreach ((array)$nodes as $removalNode) {
                $uow->scheduleForDelete($removalNode);
            }
        }

        $this->shiftRL($em, $config['useObjectClass'], $rightValue + 1, -$diff, $rootId);*/
    }

    /**
     * {@inheritdoc}
     */
    public function onFlushEnd($em)
    {}

    /**
     * {@inheritdoc}
     */
    public function processPreRemove($em, $node)
    {}

    /**
     * {@inheritdoc}
     */
    public function processPrePersist($em, $node)
    {}
}

<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ODM\MongoDB;

use MMoussa\Doctrine\Test\QueryBuilderMocker as BaseQueryBuilderMocker;
use PHPUnit_Framework_TestCase;

/**
 * Mocks Doctrine MongoDB ODM QueryBuilder fluent interface invocations for use in PHPUnit tests.
 */
class QueryBuilderMocker extends BaseQueryBuilderMocker
{
    /**
     * Methods supported for the query builder mocker.
     *
     * @var array
     */
    public static $supportedMethods = array(
        'requireIndexes',
        'field',
        'prime',
        'hydrate',
        'refresh',
        'find',
        'findAndUpdate',
        'returnNew',
        'findAndRemove',
        'update',
        'insert',
        'remove',
        'references',
        'includesReferenceTo',
        'getQuery',
        'addAnd',
        'addManyToSet',
        'addNor',
        'addOr',
        'addToSet',
        'all',
        'count',
        'distanceMultiplier',
        'distinct',
        'eagerCursor',
        'elemMatch',
        'equals',
        'exclude',
        'exists',
        'finalize',
        'geoIntersects',
        'geoNear',
        'geoWithin',
        'geoWithinBox',
        'geoWithinCenter',
        'geoWithinCenterSphere',
        'geoWithinPolygon',
        'getNewObj',
        'setNewObj',
        'setQueryArray',
        'group',
        'gt',
        'gte',
        'hint',
        'immortal',
        'in',
        'inc',
        'limit',
        'lt',
        'lte',
        'map',
        'mapReduce',
        'mapReduceOptions',
        'maxDistance',
        'mod',
        'multiple',
        'near',
        'nearSphere',
        'not',
        'notEqual',
        'notIn',
        'out',
        'popFirst',
        'popLast',
        'pull',
        'pullAll',
        'push',
        'pushAll',
        'range',
        'reduce',
        'rename',
        'select',
        'selectElemMatch',
        'selectSlice',
        'set',
        'setReadPreference',
        'size',
        'skip',
        'slaveOkay',
        'snapshot',
        'sort',
        'spherical',
        'type',
        'unsetField',
        'upsert',
        'where',
        'withinBox',
        'withinCenter',
        'withinCenterSphere',
        'withinPolygon',
        'execute',
    );

    /**
     * Initializes the TestCase and creates a mock QueryBuilder and Query for later use.
     *
     * @param PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->queryBuilder = $testCase->getMockBuilder('Doctrine\ODM\MongoDB\Query\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->query = $testCase->getMockBuilder('Doctrine\ODM\MongoDB\Query\Query')
            ->disableOriginalConstructor()
            ->setMethods(array('execute'))
            ->getMock();
    }

    /**
     * {@inheritDoc}
     *
     * @param array|null $args
     * @throws \InvalidArgumentException
     * @return $this
     */
    protected function execute(array $args)
    {
        $invocationMocker = $this->query->expects($this->testCase->once())
            ->method('execute');

        // QueryBuilderMocker "execute" parameter is the intended final result to return.
        if (count($args) > 0) {
            $invocationMocker->will($this->testCase->returnValue($args[0]));
        }

        return $this;
    }
}

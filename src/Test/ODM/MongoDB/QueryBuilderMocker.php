<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ODM\MongoDB;

use MMoussa\Doctrine\Test\QueryBuilderMocker as BaseQueryBuilderMocker;
use PHPUnit\Framework\TestCase;

/**
 * Mocks Doctrine MongoDB ODM QueryBuilder fluent interface invocations for use in PHPUnit tests.
 * \Doctrine\ODM\MongoDB\Query\Query
 * @method requireIndexes
 * @method field
 * @method prime
 * @method hydrate
 * @method refresh
 * @method find
 * @method findAndUpdate
 * @method returnNew
 * @method findAndRemove
 * @method update
 * @method insert
 * @method remove
 * @method references
 * @method includesReferenceTo
 * @method getQuery
 * @method addAnd
 * @method addManyToSet
 * @method addNor
 * @method addOr
 * @method addToSet
 * @method all
 * @method count
 * @method distanceMultiplier
 * @method distinct
 * @method eagerCursor
 * @method elemMatch
 * @method equals
 * @method exclude
 * @method exists
 * @method finalize
 * @method geoIntersects
 * @method geoNear
 * @method geoWithin
 * @method geoWithinBox
 * @method geoWithinCenter
 * @method geoWithinCenterSpher
 * @method geoWithinPolygon
 * @method getNewObj
 * @method setNewObj
 * @method setQueryArray
 * @method group
 * @method gt
 * @method gte
 * @method hint
 * @method immortal
 * @method in
 * @method inc
 * @method limit
 * @method lt
 * @method lte
 * @method map
 * @method mapReduce
 * @method mapReduceOptions
 * @method maxDistance
 * @method mod
 * @method multiple
 * @method near
 * @method nearSphere
 * @method not
 * @method notEqual
 * @method notIn
 * @method out
 * @method popFirst
 * @method popLast
 * @method pull
 * @method pullAll
 * @method push
 * @method pushAll
 * @method range
 * @method reduce
 * @method rename
 * @method select
 * @method selectElemMatch
 * @method selectSlice
 * @method set
 * @method setReadPreference
 * @method size
 * @method skip
 * @method slaveOkay
 * @method snapshot
 * @method sort
 * @method spherical
 * @method type
 * @method unsetField
 * @method upsert
 * @method where
 * @method withinBox
 * @method withinCenter
 * @method withinCenterSphere
 * @method withinPolygon
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
        'getSingleResult',
        'getOneOrNullResult'
    );

    /**
     * Initializes the TestCase and creates a mock QueryBuilder and Query for later use.
     *
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->queryBuilder = $testCase->getMockBuilder('Doctrine\ODM\MongoDB\Query\Builder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->query = $testCase->getMockBuilder('Doctrine\ODM\MongoDB\Query\Query')
            ->disableOriginalConstructor()
            ->setMethods(array('execute', 'getSingleResult', 'getOneOrNullResult'))
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

    /**
     * {@inheritDoc}
     *
     * @param array|null $args
     * @return $this
     */
    protected function getSingleResult(array $args)
    {
        $invocationMocker = $this->query->expects($this->testCase->once())->method('getSingleResult');

        // QueryBuilderMocker "getSingleResult" parameter is the intended final result to return.
        if (count($args) > 0) {
            $invocationMocker->will($this->testCase->returnValue($args[0]));
        }

        return $this;
    }

    /**
     * @param array|null $args
     * @return $this
     */
    protected function getOneOrNullResult(array $args)
    {
        $invocationMocker = $this->query->expects($this->testCase->once())->method('getOneOrNullResult');

        // QueryBuilderMocker "getOneOrNullResult" parameter is the intended final result to return.
        if (count($args) > 0) {
            $invocationMocker->will($this->testCase->returnValue($args[0]));
        }

        return $this;
    }
}

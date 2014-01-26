<?php
/**
 * Simplified mocking for unit tests involving the Doctrine MongoDB ODM QueryBuilder.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ODM\MongoDB;

use BadMethodCallException;
use Doctrine\ODM\MongoDB\Query\Builder;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Mocks Doctrine MongoDB ODM QueryBuilder fluent interface invocations for use in PHPUnit tests.
 */
class QueryBuilderMocker
{
    /**
     * Counter keeping track of method invocation order.
     *
     * @var int
     */
    protected $at = 0;

    /**
     * Instance of the TestCase in which this QueryBuilderMock is going to be used.
     *
     * @var PHPUnit_Framework_TestCase
     */
    protected $testCase;

    /**
     * Mocked QueryBuilder
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $queryBuilder;

    /**
     * Mocked Query
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $query;

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
            ->getMock();
    }

    /**
     * Magic method that sets up all the expectations.
     *
     * @param string $method
     * @param array $args
     * @return $this
     * @throws BadMethodCallException If attempting to mock an unsupported method.
     */
    public function __call($method, array $args)
    {
        if (!in_array($method, $this::$supportedMethods)) {
            throw new BadMethodCallException('Mocking "' . $method . '" is not supported.');
        }

        if ($method === 'execute') {
            $invocationMocker = $this->query->expects($this->testCase->once())
                ->method('execute');

            // QueryBuilderMocker "execute" parameter is the intended final result to return.
            if (count($args) > 0) {
                $invocationMocker->will($this->testCase->returnValue($args[0]));
            }

            return $this;
        }

        $invocationMocker = $this->queryBuilder->expects($this->testCase->at($this->at))
            ->method($method);
        $this->at++; // increment to maintain expected execution order

        // For ->with(...) expectation
        if (count($args) > 0) {
            $invocationMocker = call_user_func_array(array($invocationMocker, 'with'), $args);
        }

        if ($method === 'getQuery') {
            $invocationMocker->will($this->testCase->returnValue($this->query));
        } else {
            $invocationMocker->will($this->testCase->returnValue($this->queryBuilder));
        }

        return $this;
    }

    /**
     * Returns the final mocked query builder.
     *
     * @return Builder
     */
    public function getQueryBuilderMock()
    {
        return $this->queryBuilder;
    }
}

<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test;

use BadMethodCallException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Base class for mocking Doctrine QueryBuilder fluent interface invocations for use in PHPUnit tests.
 */
abstract class QueryBuilderMocker
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
    public static $supportedMethods = array();

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
            return $this->execute($args);
        }

        if ($method === 'getSingleResult') {
            return $this->getSingleResult($args);
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
     * @return \Doctrine\ODM\MongoDB\Query\Builder|\Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderMock()
    {
        return $this->queryBuilder;
    }

    /**
     * Handles mocking of execute() calls, which are a special case.
     *
     * @param array|null $args
     * @return \Doctrine\ODM\MongoDB\Query\Builder|\Doctrine\ORM\QueryBuilder
     */
    abstract protected function execute(array $args);

    /**
     * Handles mocking of getSingleResult calls, which are another special case
     *
     * @param array|null $args
     * @return \Doctrine\ODM\MongoDB\Query\Builder|\Doctrine\ORM\QueryBuilder
     */
    abstract protected function getSingleResult(array $args);
}

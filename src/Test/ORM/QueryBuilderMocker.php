<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ORM;

use MMoussa\Doctrine\Test\QueryBuilderMocker as BaseQueryBuilderMocker;
use PHPUnit_Framework_TestCase;

/**
 * Mocks Doctrine ORM QueryBuilder fluent interface invocations for use in PHPUnit tests.
 */
class QueryBuilderMocker extends BaseQueryBuilderMocker
{
    /**
     * Methods supported for the query builder mocker.
     *
     * @var array
     */
    public static $supportedMethods = array(
        'getQuery',
        'setParameter',
        'setParameters',
        'setFirstResult',
        'setMaxResults',
        'add',
        'select',
        'distinct',
        'addSelect',
        'delete',
        'update',
        'from',
        'join',
        'innerJoin',
        'leftJoin',
        'set',
        'where',
        'andWhere',
        'orWhere',
        'groupBy',
        'addGroupBy',
        'having',
        'andHaving',
        'orHaving',
        'orderBy',
        'addOrderBy',
        'addCriteria',
        'execute',
        'useResultCache',
        'getSingleResult',
        'getSingleScalarResult',
    );

    /**
     * Initializes the TestCase and creates a mock QueryBuilder and Query for later use.
     *
     * @param PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->queryBuilder = $testCase->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->query = $testCase->getMockBuilder('StubQuery') // can't mock Doctrine's "Query" because it's "final"
            ->setMethods(array('execute', 'useResultCache', 'getSingleResult', 'getSingleScalarResult'))
            ->disableOriginalConstructor()
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
        $invocationMocker = $this->query
            ->expects($this->testCase->once())
            ->method('execute');

        if (empty($args)) {
            return $this;
        }

        if (count($args) > 1) {
            $executeArgs = is_array($args[0]) ? $args[0] : array($args[0]);
            $result = isset($args[1]) ? $args[1] : null;

            call_user_func_array(array($invocationMocker, 'with'), $executeArgs);
        } else {
            $result = $args[0];
        }

        $invocationMocker->will($this->testCase->returnValue($result));
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
     * @param array $args
     * @return $this
     */
    protected function getSingleScalarResult(array $args)
    {
        $invocationMocker = $this->query->expects($this->testCase->once())->method('getSingleScalarResult');

        if (count($args) > 0) {
            $invocationMocker->will($this->testCase->returnValue($args[0]));
        }

        return $this;
    }

    /**
     * Override for methods that are specific to ORM
     *
     * @param string $method
     * @param array $args
     * @return $this|QueryBuilderMocker
     */
    public function __call($method, array $args)
    {
        if ($method === 'getSingleScalarResult') {
            return $this->getSingleScalarResult($args);
        }

        return parent::__call($method, $args);
    }
}

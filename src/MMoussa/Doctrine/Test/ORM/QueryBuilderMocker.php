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
            ->setMethods(array('execute'))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param array $args
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

            $invocationMocker->will($this->testCase->returnValue($result));
        } elseif (count($args) == 1) {
            $invocationMocker->will($this->testCase->returnValue($args[0]));
        }

        return $this;
    }
}

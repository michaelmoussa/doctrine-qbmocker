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
}

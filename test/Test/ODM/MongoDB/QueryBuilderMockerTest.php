<?php
/**
 * Simplified mocking for unit tests involving the Doctrine MongoDB ODM QueryBuilder.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ODM\MongoDB;

use PHPUnit_Framework_TestCase;

class QueryBuilderMockerTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetInstanceOfTheQueryBuilderMock()
    {
        $qbm = new QueryBuilderMocker($this);
        $this->assertInstanceOf('Doctrine\ODM\MongoDB\Query\Builder', $qbm->getQueryBuilderMock());
    }

    public function testCanMockChainedMethodCalls()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->select('fieldName')
            ->field('property')->equals('value')
            ->field('otherProperty')->equals('otherValue');

        $qb = $qbm->getQueryBuilderMock();
        $qb->select('fieldName')
            ->field('property')->equals('value')
            ->field('otherProperty')->equals('otherValue');
    }

    public function testMockedGetQueryReturnsQueryObject()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery();

        $qb = $qbm->getQueryBuilderMock();

        $this->assertInstanceOf('Doctrine\ODM\MongoDB\Query\Query', $qb->getQuery());
    }

    public function testValuePassedToMockedExecuteMethodIsReturnedByExecute()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute('someReturnValue');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertSame('someReturnValue', $qb->getQuery()->execute());
    }

    public function testMockingExecuteWithNoParamWillCauseItToReturnNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute();

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->getQuery()
                     ->execute();

        $this->assertNull($result);
    }

    public function testValuePassedToMockedGetSingleResultMethodIsReturned()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getSingleResult('someReturnValue');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertSame('someReturnValue', $qb->getQuery()->getSingleResult());
    }

    public function testMockingGetSingleResultWithNoParamWillCauseItToReturnNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getSingleResult();

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->getQuery()->getSingleResult();

        $this->assertNull($result);
    }

    public function testValuePassedToMockingGetOneOrNullResultIsReturned()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getOneOrNullResult(123);

        $qb = $qbm->getQueryBuilderMock();
        $this->assertEquals(123, $qb->getQuery()->getOneOrNullResult());
    }

    public function testMockingGetOneOrNullResultWithNoParamCauseItToReturnNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getOneOrNullResult();

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->getQuery()->getOneOrNullResult();
        $this->assertNull($result);
    }

    public function testCanMockChainedMethodCallsToQuery()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->select('fieldName')
            ->field('property')->equals('value')
            ->field('otherProperty')->equals('otherValue')
            ->getQuery()
            ->execute('it works!');

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->select('fieldName')
                     ->field('property')->equals('value')
                     ->field('otherProperty')->equals('otherValue')
                     ->getQuery()
                     ->execute();

        $this->assertSame('it works!', $result);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Mocking "expr" is not supported.
     */
    public function testBadMethodCallExceptionIsThrownIfAttemptingToMockUnsupportedMethod()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->expr();
    }
}

<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ORM;

use PHPUnit_Framework_TestCase;

class QueryBuilderMockerTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetInstanceOfTheQueryBuilderMock()
    {
        $qbm = new QueryBuilderMocker($this);
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qbm->getQueryBuilderMock());
    }

    public function testCanMockChainedMethodCalls()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->select('fieldName')
            ->where('property = ?')
            ->andWhere('otherProperty = :otherValue');

        $qb = $qbm->getQueryBuilderMock();
        $qb->select('fieldName')
            ->where('property = ?')
            ->andWhere('otherProperty = :otherValue');
    }

    public function testMockedGetQueryReturnsStubQueryObject()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery();

        $qb = $qbm->getQueryBuilderMock();

        $this->assertInstanceOf('StubQuery', $qb->getQuery());
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

    public function testCanMockChainedMethodCallsToQuery()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->select('fieldName')
            ->where('property = ?')
            ->andWhere('otherProperty =:otherValue')
            ->getQuery()
            ->execute('it works!');

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->select('fieldName')
            ->where('property = ?')
            ->andWhere('otherProperty =:otherValue')
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

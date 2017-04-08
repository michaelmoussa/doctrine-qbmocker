<?php
/**
 * Simplified mocking for unit tests involving the Doctrine QueryBuilders.
 *
 * @author Michael Moussa <michael.moussa@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 */

namespace MMoussa\Doctrine\Test\ORM;

use PHPUnit\Framework\TestCase;

class QueryBuilderMockerTest extends TestCase
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

    public function testEmptyExecuteIsReturned()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute();

        $qb = $qbm->getQueryBuilderMock();

        $this->assertNull($qb->getQuery()->execute());
    }

    public function testStringParamForExecuteIsConvertedToArray()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute(null, 'result');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertSame('result', $qb->getQuery()->execute(null, 'result'));
    }

    public function testSingleParamToExecuteActsAsResult()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute('result');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertSame('result', $qb->getQuery()->execute('result'));
    }

    public function testCanPassBothParamsAndReturnValueToExecute()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute(array(array('prop1' => 'value1', 'prop2' => 'value2')), 'result');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertSame('result', $qb->getQuery()->execute(array('prop1' => 'value1', 'prop2' => 'value2')));
    }

    public function testGetSingleResultReturnsValue()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->getSingleResult('it works!');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertEquals('it works!', $qb->getQuery()->getSingleResult());
    }

    public function testGetSingleScalarResultReturnsValue()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->getSingleScalarResult('it works!');

        $qb = $qbm->getQueryBuilderMock();

        $this->assertEquals('it works!', $qb->getQuery()->getSingleScalarResult());
    }

    public function testEmptyGetSingleResultIsNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->getSingleResult();

        $qb = $qbm->getQueryBuilderMock();

        $this->assertNull($qb->getQuery()->getSingleResult());
    }

    public function testGetArrayResultReturnsValue()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->getArrayResult(array(1, 2, 3));

        $qb = $qbm->getQueryBuilderMock();

        $this->assertEquals(array(1, 2, 3), $qb->getQuery()->getArrayResult());
    }

    public function testEmptyGetArrayResultIsNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->getArrayResult();

        $qb = $qbm->getQueryBuilderMock();

        $this->assertNull($qb->getQuery()->getArrayResult());
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

    public function testValuePassedToMockingGetResultIsReturned()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getResult(123);

        $qb = $qbm->getQueryBuilderMock();
        $this->assertEquals(123, $qb->getQuery()->getResult());
    }

    public function testMockingGetResultWithNoParamCauseItToReturnNull()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()->getResult();

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->getQuery()->getResult();
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

    public function testExprReturnsDoctrineExpressionInstance()
    {
        $qbm = new QueryBuilderMocker($this);
        $expr = $qbm->expr();

        $qb = $qbm->getQueryBuilderMock();
        $result = $qb->expr();
        $this->assertInstanceOf('Doctrine\Orm\Query\Expr', $result);
        $this->assertSame($expr, $result);
    }

    public function testExprWorksProperlyWhenUsedInsideWhere()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->andWhere($qbm->expr()->isNull('foo'));

        $qb = $qbm->getQueryBuilderMock();

        $expression = $qb->expr()->isNull('foo');
        $this->assertEquals('foo IS NULL', $expression);
        $qb->andWhere($expression);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Mocking "foo" is not supported.
     */
    public function testBadMethodCallExceptionIsThrownIfAttemptingToMockUnsupportedMethod()
    {
        $qbm = new QueryBuilderMocker($this);
        $qbm->foo();
    }

    /**
     * @author Lauri Orgla <theorx@hotmail.com>
     * @covers \MMoussa\Doctrine\Test\ORM\QueryBuilderMocker::getQueryMock
     */
    public function testGetQueryMockReturnsMockObject()
    {

        $qbm = new QueryBuilderMocker($this);
        $qbm->getQuery()
            ->execute();

        $queryMock = $qbm->getQueryMock();
        $queryBuilderMock = $qbm->getQueryBuilderMock();
        $this->assertInstanceOf('\PHPUnit_Framework_MockObject_MockObject', $queryMock);
        $this->assertNull($queryBuilderMock->getQuery()->execute());
    }
}

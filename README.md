[![Build Status](https://travis-ci.org/michaelmoussa/doctrine-qbmocker.png?branch=master)](https://travis-ci.org/michaelmoussa/doctrine-qbmocker)
[![Coverage Status](https://coveralls.io/repos/michaelmoussa/doctrine-qbmocker/badge.png)](https://coveralls.io/r/michaelmoussa/doctrine-qbmocker)
[![Latest Stable Version](https://poser.pugx.org/michaelmoussa/doctrine-qbmocker/v/stable.png)](https://packagist.org/packages/michaelmoussa/doctrine-qbmocker)
# Doctrine QueryBuilderMocker

### What's this?

A tool for easy mocking of Doctrine QueryBuilder objects to facilitate unit testing.

### Example

Suppose you have a collection of `user` accounts with documents looking like this:

```
{
    firstName: "John",
    lastName: "Doe",
    email: "JohnDoe@example.com",
    country: "USA"
}
```

You have a `User` service class with a method that returns all names and e-mail address of users in a particular country, sorted by lastName, firstName:

```
public function getUsersByCountry($country)
{
    return $this->documentManager->createQueryBuilder('My\Document\User')
                                 ->select('firstName', 'lastName', 'email')
                                 ->field('country')->equals($country)
                                 ->sort('lastName', 'firstName')
                                 ->getQuery()
                                 ->execute();
}
```

Simple enough. Now how do we test it?

We could create a test database, add a few test records, then run the method to see if it retrieves the right data, but that's not ideal.

1. Connecting to a database in a test is relatively slow. Too many such tests in a test suite can cause it to really drag.
1. It only tests that you're getting the expected data back - not that your method is retrieving it correctly.
    * What if someone accidentally deletes the `->field('country')->equals($country)`? Or changes it to `->gte($country)`? Or removes the `->sort('lastName', 'firstName')`? 
    * Depending on what test records you created and the order in which you created them, you may still get the expected results back even though your method is not retrieving them the way you'd expect. Your test would still pass, but the code would be wrong.

The better way to test this is to mock the expected method calls and make sure the Query Builder is being used correctly.

But that ends up being pretty messy... take a look:

```
public function testCanGetSortedUsersByCountry()
{
    $mockQuery = $this->getMockBuilder('Doctrine\ODM\MongoDB\Query\Builder')
                      ->disableOriginalConstructor()
                      ->getMock();
    $mockQuery->expects($this->once())
              ->method('execute')
              ->will($this->returnValue('it works!'));
    
    $mockQueryBuilder = $this->getMockBuilder('Doctrine\ODM\MongoDB\Query\Builder')
                             ->disableOriginalConstructor()
                             ->getMock();
    $mockQueryBuilder->expects($this->at(0))
                     ->method('select')
                     ->with('firstName', 'lastName', 'email')
                     ->will($this->returnValue($mockQueryBuilder));
    $mockQueryBuilder->expects($this->at(1))
                     ->method('field')
                     ->with('country')
                     ->will($this->returnValue($mockQueryBuilder));
    $mockQueryBuilder->expects($this->at(2))
                     ->method('equals')
                     ->with('USA')
                     ->will($this->returnValue($mockQueryBuilder));
    $mockQueryBuilder->expects($this->at(3))
                     ->method('sort')
                     ->with('lastName', 'firstName')
                     ->will($this->returnValue($mockQueryBuilder));
    $mockQueryBuilder->expects($this->at(4))
                     ->method('getQuery')
                     ->will($this->returnValue($mockQuery));
    
    $mockDocumentManager = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
                                ->disableOriginalConstructor()
                                ->getMock();
    $mockDocumentManager->expects($this->once())
                        ->method('createQueryBuilder')
                        ->with('My\Document\User')
                        ->will($this->returnValue($mockQueryBuilder));
    
    $service->setDocumentManager($mockDocumentManager);
    
    $this->assertSame('it works!', $this->service->getUsersByCountry('USA'));
```

Imagine a unit test class filled with dozens of those! Surely we can do better. How about this?

```
public function testCanGetSortedUsersByCountry()
{
    $qbm = new QueryBuilderMock($this);
    $qbm->select('firstName', 'lastName', 'email')
        ->field('country')->equals($country)
        ->sort('lastName', 'firstName')
        ->getQuery()
        ->execute('it works!');
    $qb = $qbm->getQueryBuilderMock();
    
    $this->assertSame('it works!', $this->service->getUsersByCountry('USA'));
}
```

Short and concise! Notice that it looks almost identical to the calls being made in the actual service class: The only difference is that `execute()` accepts a parameter, which ends up becoming the value that the mocked call to `execute()` will return. Mocking a method's usage of the Query Builder (or vice-versa, for those of us who TDD) is as simple as copying-and-pasting.

Prefer MySQL? This library supports mocking Doctrine ORM query builders as well. Just use `MMoussa\Doctrine\Test\ORM\QueryBuilderMocker` instead of `MMoussa\Doctrine\Test\ODM\MongoDB\QueryBuilderMocker`!

### Installation

The only supported method of installation is Composer: `composer require "michaelmoussa/doctrine-qbmocker:0.*" --dev`

### Dependencies

* Using this library for mocking the ORM query builder requires you to install `"doctrine/orm": "0.2.*"` as well.
* Using this library for mocking the ODM query builder requires you to install `"doctrine/mongodb-odm": "dev-master"` and `"jmikola/geojson": "~1.0"` as well.

### Contributing

Contributions are welcome. There are several unsupported methods still that would be non-trivial to implement. Please feel free to take care of those and send a PR.

All contributions must conform to PSR2 and include 100% test coverage.

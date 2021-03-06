<?php

namespace DavidBadura\FixturesBundle\Tests\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureData;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var DefaultConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new DefaultConverter();
    }

    public function testDefaultConverterCreateObject()
    {

        $data = $this->getMock('DavidBadura\FixturesBundle\FixtureData', array('getProperties'), array(
            'test',
            array(
                'name' => 'test_name',
                'email' => 'test_email',
                'groups' => array('xyz', 'abc')
            )
        ));

        $data->expects($this->any())->method('getProperties')->will($this->returnValue(array(
            'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\User',
            'constructor' => array('name', 'email')
        )));

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertEquals('test_email', $object->getEmail());

        $this->converter->finalizeObject($object, $data);

        $this->assertEquals(array('xyz', 'abc'), $object->getGroups());
    }

    public function testDefaultConverterCreateObject_UniqueId()
    {

        $data = $this->getMock('DavidBadura\FixturesBundle\FixtureData', array('getProperties'), array(
            'test',
            array(
                'name' => 'test_name {unique_id}',
                'email' => 'test_email',
                'description'   => 'test_description {unique_id}'
            )
        ));

        $data->expects($this->any())->method('getProperties')->will($this->returnValue(array(
            'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\User',
            'constructor' => array('name', 'email')
        )));

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $object);
        $this->assertRegExp('/test_name .{13}/', $object->getName());
        $this->assertEquals('test_email', $object->getEmail());

        $this->converter->finalizeObject($object, $data);

        $this->assertRegExp('/test_description .{13}/', $object->getDescription());
    }

    public function testDateTimeConstructor()
    {

        $data = $this->getMock('DavidBadura\FixturesBundle\FixtureData', array('getProperties'), array(
            'test',
            array(
                'name' => 'test_name',
                'date' => 'now'
            )
        ));

        $data->expects($this->any())->method('getProperties')->will($this->returnValue(array(
            'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\Post',
            'constructor' => array('name', 'date')
        )));

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\Post', $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertInstanceOf('DateTime', $object->getDate());
    }

}

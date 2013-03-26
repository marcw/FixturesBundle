<?php

namespace DavidBadura\FixturesBundle\EventListener;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use DavidBadura\FixturesBundle\Util\ObjectAccess\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class SecurityListener
{

    /**
     * @var EncoderFactoryInterface
     */
    private $factory;

    /**
     *
     * @param EncoderFactoryInterface $factory
     */
    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     *
     * @return EncoderFactoryInterface
     */
    public function getEncoderFactory()
    {
        return $this->factory;
    }

    /**
     *
     * @param PostExecuteEvent $event
     */
    public function onPostExecute(PostExecuteEvent $event)
    {
        $fixtures = $event->getFixtures();

        foreach ($fixtures as $fixture) {

            $properties = $fixture->getProperties();

            if(!isset($properties['security']) || $properties['security'] == false) {
                continue;
            }

            $passwordField = isset($properties['security']['password']) ? $properties['security']['password'] : 'password';
            $saltField = isset($properties['security']['salt']) ? $properties['security']['salt'] : 'salt';

            foreach ($fixture as $data) {
                $object = $data->getObject();

                if (!$object) {
                    continue;
                }

                $encoder = $this->factory->getEncoder($object);
                $access = new ObjectAccess($object);

                $password = $encoder->encodePassword($access->readProperty($passwordField), $access->readProperty($saltField));
                $access->writeProperty($passwordField, $password);
            }
        }
    }

}

<?php


namespace Core\Validator\DoctrineValidator\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Core\Validator\DoctrineValidator\Exception\ServiceCreationException;
use Laminas\Stdlib\ArrayUtils;

/**
 * Factory for creating NoObjectExists instances
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   1.3.0
 * @author  Fabian Grutschus <f.grutschus@lubyte.de>
 */
abstract class AbstractValidatorFactory implements FactoryInterface
{
    const DEFAULT_OBJECTMANAGER_KEY = 'doctrine.entitymanager.orm_default';

    protected $creationOptions = [];

    protected $validatorClass;

    /**
     * @param ContainerInterface $container
     * @param array $options
     * @return \Doctrine\Common\Persistence\ObjectRepository
     * @throws ServiceCreationException
     */
    protected function getRepository(ContainerInterface $container, array $options = null)
    {
        if (empty($options['target_class'])) {
            throw new ServiceCreationException(sprintf(
                "Option 'target_class' is missing when creating validator %s",
                __CLASS__
            ));
        }

        $objectManager    = $this->getObjectManager($container, $options);
        $targetClassName  = $options['target_class'];
        $objectRepository = $objectManager->getRepository($targetClassName);

        return $objectRepository;
    }

    /**
     * @param ContainerInterface $container
     * @param array $options
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getObjectManager(ContainerInterface $container, array $options = null)
    {
        $objectManager = ($options['object_manager']) ?? self::DEFAULT_OBJECTMANAGER_KEY;

        if (is_string($objectManager)) {
            $objectManager = $container->get($objectManager);
        }

        return $objectManager;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getFields(array $options)
    {
        if (isset($options['fields'])) {
            return (array) $options['fields'];
        }

        return [];
    }

    /**
     * Helper to merge options array passed to `__invoke`
     * together with the options array created based on the above
     * helper methods.
     *
     * @param array $previousOptions
     * @param array $newOptions
     * @return array
     */
    protected function merge($previousOptions, $newOptions)
    {
        return ArrayUtils::merge($previousOptions, $newOptions, true);
    }

    /**
     * @param ContainerInterface $container
     * @return ContainerInterface
     */
    protected function container(ContainerInterface $container)
    {
        return $container;
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, $this->validatorClass, $this->creationOptions);
    }

    public function setCreationOptions(array $options)
    {
        $this->creationOptions = $options;
    }
}

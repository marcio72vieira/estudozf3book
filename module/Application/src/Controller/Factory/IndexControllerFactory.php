<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\MailSender;
use Application\Controller\IndexController;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, 
                             $requestedName, array $options = null)
    {
        $mailSender = $container->get(MailSender::class);
        
        // Instantiate the controller and inject dependencies
        return new IndexController($mailSender);
    }
}
<?php

declare(strict_types=1);

namespace Application\Controller;
 
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
 

// Ao criar este controller, é necessário registrá-lo no arquivo module/Application/config/module.config.php dentro das
// chaves: controllers; factorys, como na linha abaixo:
// Controller\EstudoController::class => InvokableFactory::class
class EstudoController extends AbstractActionController
{

    public function recoverInformationsAction() 
    {
        echo "Olá";
    }
}
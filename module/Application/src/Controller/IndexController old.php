<?php

declare(strict_types=1);

namespace Application\Controller;
 
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
 
class IndexController extends AbstractActionController

// Descomente a chave: 'strategies' no arquivo module.config.php e digite a linha abaixo, para retornar  dados no formato json:
// a partir do método 'getJsonAction()' localizado logo abaixo
// http://localhost:8000/application/get-json 

{
    public function indexAction()
    {
        //Template original
        //return new ViewModel();

        //Definindo um template diferente para a página de inicialização
        $meuNome = "Marcio Vieira";
        $viewModel = new ViewModel(['meuNome' => $meuNome]);
	    $viewModel->setTemplate('application/index/newtemplate');
        return $viewModel;
    }

    public function aboutAction() 
    {              
        $appAparencia = '2';
        $appName = 'Usando o Zend Framework 3';
        $appDescription = 'Uma simples aplicação para utilizar o livro do Zend Framework 3';
        
        // Return variables to view script with the help of
        // ViewModel variable container
        return new ViewModel([
            'appAparencia' => $appAparencia,
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }

    public function getJsonAction()
    {
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }
}

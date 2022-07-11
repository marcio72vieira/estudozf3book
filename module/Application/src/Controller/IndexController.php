<?php

declare(strict_types=1);

namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
// Add name alias in the beginning of the file para o exemplo do barcode
use Laminas\Barcode\Barcode;
//Para alterar o layout de todos os métodos deste controller
use Laminas\Mvc\MvcEvent;



class IndexController extends AbstractActionController

{
    /** 
   * We override the parent class' onDispatch() method to
   * set an alternative layout for all actions in this controller. Utilizado com: use Laminas\Mvc\MvcEvent;
   */
    public function onDispatch(MvcEvent $e) 
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);        
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/layout2');                
        
        // Return the response
        return $response;
    }


    public function indexAction()
    {
        //Template original
        //return new ViewModel();


        //Definindo um template diferente para a página de inicialização
        //Definimos explicitamente o caminho e o nome do modelo de visualização para renderização.
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

    public function newsAction()
    {
        return new ViewModel();
    }

    
    //EXEMPLO BARCODE. Para executar este exemplo, digite na URL do seu navegador a linha abaixo:
    //http://localhost:8000/barcode
    
    // The "barcode" action
    public function barcodeAction() 
    {
    // Get parameters from route.
        $type = $this->params()->fromRoute('type', 'code39');
        $label = $this->params()->fromRoute('label', 'HELLO-WORLD');
            
        // Set barcode options.
        $barcodeOptions = ['text' => $label];        
        $rendererOptions = [];
            
        // Create barcode object
        $barcode = Barcode::factory($type, 'image', 
                    $barcodeOptions, $rendererOptions);
            
        // The line below will output barcode image to standard 
        // output stream.
        $barcode->render();

        // Return Response object to disable default view rendering. 
        return $this->getResponse();
    }
    
    
    // http://localhost:8000/doc/?page=contents.html . Esta URL pelo menos apresenta a página com erro 404 padrão da apliação Laminas
    public function docAction() 
    {
        $pageTemplate = 'application/index/doc'.$this->params()->fromRoute('page', 'documentation.phtml');        
    
        $filePath = __DIR__.'/../../view/'.$pageTemplate.'.phtml';

        if(!file_exists($filePath) || !is_readable($filePath)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $viewModel = new ViewModel([
                'page'=>$pageTemplate
            ]);
        $viewModel->setTemplate($pageTemplate);
        
        return $viewModel;
    }


    // http://localhost/public/static/help
    public function staticAction() 
    {
        // Get path to view template from route params
        $pageTemplate = $this->params()->fromRoute('page', null);
        if($pageTemplate==null) {
            $this->getResponse()->setStatusCode(404); 
            return;
        }
        
        // Render the page
        $viewModel = new ViewModel([
                'page'=>$pageTemplate
            ]);
        $viewModel->setTemplate($pageTemplate);
        return $viewModel;
    }


    public function administratorAction()
    {
        // Use the Layout plugin to access the ViewModel object associated with layout template.
        $this->layout()->setTemplate('layout/layout2');
    }


    // An action that demonstrates the usage of partial views.
    public function partialDemoAction() 
    {
    $products = [
        [
        'id' => 1,
        'name' => 'Digital Camera',
        'price' => 99.95,
        ],
        [
        'id' => 2,
        'name' => 'Tripod',
        'price' => 29.95,
        ],
        [
        'id' => 3,
        'name' => 'Camera Case',
        'price' => 2.99,
        ],
        [
        'id' => 4,
        'name' => 'Batteries',
        'price' => 39.99,
        ],
        [
        'id' => 5,
        'name' => 'Charger',
        'price' => 29.99,
        ],
    ];
        
    return new ViewModel(['products' => $products]);
    }

    public function recoverInformationsAction() 
    {
        ##### Dados da Requisição $request
        // Recuperando todos os dados da Requisição
        $request = $this->getRequest();

        #####Plug-in Params
        // Recuperando uma variável enviada via GET, se a variável não for encontrada o valor default será retornado
        $variavelViaGet = $this->params()->fromQuery('name', 'Não há parâmetro via get name');
        // Recuperando uma variável enviada via POST, se a variável não for encontrada o valor default será retornado
        $variavelViaPost = $this->params()->fromPost('name', 'Não há parâmetro via post name');

        ##### Dados da Resposta $response
        // Recuperando todos os dados da Requisição
        $response = $this->getResponse(); 

        //Enviando alguns dados capturados e configurados para a view
        $viewModel = new ViewModel(['request' => $request, 
                                    'variavelViaGet' => $variavelViaGet,
                                    'variavelViaPost' => $variavelViaPost,
                                    'response' => $response]);
        $viewModel->setTemplate('application/index/estudotemplate');

        return $viewModel;
    }
    
}

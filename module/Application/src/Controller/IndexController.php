<?php

declare(strict_types=1);

namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
// Add name alias in the beginning of the file para o exemplo do barcode
use Laminas\Barcode\Barcode;
//Para alterar o layout de todos os métodos deste controller
use Laminas\Mvc\MvcEvent;

use Application\Form\ContactForm;

use Application\Service\MailSender;
use Application\Filter\PhoneFilter;


class IndexController extends AbstractActionController

{
    /* 
    private $mailSender;
    
    public function __construct($mailSender) 
    {
        $this->mailSender = $mailSender;
    }
    */


    /** 
   * We override the parent class' onDispatch() method to
   * set an alternative layout for all actions in this controller. Utilizado com: use Laminas\Mvc\MvcEvent;
   */

    /* public function onDispatch(MvcEvent $e) 
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);        
        
        // Set alternative layout
        //$this->layout()->setTemplate('layout/layout2');
        $this->layout()->setTemplate('layout/layout3');  
        
        // Return the response
        return $response;
    } */


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
    
    // O ZF3 sabe automaticamente, que a view que ele deve renderizar possui o mesmo nome da action, a mesno que
    // uma nova view seja especificada explicitamente
    return new ViewModel(['products' => $products]);
    }

    public function estudoAction() 
    {
        ##### Definindo um layout(template) alternativo para esta action
        $this->layout()->setTemplate('layout/layout2');

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

    /* 
    // This action displays the feedback form
    public function contactUsAction() 
    {
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
        
            // Retrieve form data from POST variables
            $data = $this->params()->fromPost(); 
            $data["Tipo Requisição"] = "POST";
            
            // ... Do something with the data ...
            //var_dump($data);
            echo "<pre>"; var_dump($data); echo "</pre>";
            //echo "<pre>"; var_dump($this->getRequest()); echo "</pre>";
        } 
            
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    */

  /*
  // This action displays the feedback form
  public function contactUsAction() 
  {
    // Create Contact Us form
    $form = new ContactForm();
        
    // Check if user has submitted the form
    if($this->getRequest()->isPost()) {
      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
            
      // Validate form
      if($form->isValid()) {
                
        // Get filtered and validated data
        $data = $form->getData();
                
        // ... Do something with the validated data ...
		
        // Redirect to "Thank You" page
        return $this->redirect()->toRoute('application', ['action'=>'thankYou']);
      }            
    } 
        
    // Pass form variable to view
    return new ViewModel([
          'form' => $form
       ]);
  }
  */

  public function contactUsAction() 
  {
    // Create Contact Us form
    $form = new ContactForm();
        
    // Check if user has submitted the form
    if($this->getRequest()->isPost()) {
            
      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
            
      $form->setData($data);
            
      // Validate form
      if($form->isValid()) {
                
        // Get filtered and validated data
        $data = $form->getData();
        $name = $data['name'];
        $email = $data['email'];
        $subject = $data['subject'];
        $body = $data['body'];
                
        /*
        // Send E-mail
        if(!$this->mailSender->sendMail('no-reply@example.com', $email, 
                        $subject, $body)) {
          // In case of error, redirect to "Error Sending Email" page
          return $this->redirect()->toRoute('application', 
                        ['action'=>'sendError']);
        }
        */

        // Redirect to "Thank You" page
        // return $this->redirect()->toRoute('application', 
        //                ['action'=>'thankYou']);

        //Definindo a página de Agradecimento juntamente, passando para ela os dados digitados no formulário
        //Juntamente com os dados digitados, é passado também o número de telefone já filtrado no formato 
        //Internacional. Este trecho de código, está subistituindo o trecho logo acima que invoca o método
        //thankYouAction, através do método redirect()->toRoute().

        //Fazendo uso da classe PhoneFiter em: /var/www/html/zf3/estudozf3book/module/Application/src/Filter/PhoneFilter.php
        // Create PhoneFilter filter.
        $filter = new PhoneFilter();
        // Configure the filter.
        $filter->setFormat(PhoneFilter::PHONE_FORMAT_INTL);
        // Filter a string.
        $data['phone'] = $filter->filter($data['phone']);


        $viewModel = new ViewModel(['data' => $data]);
	    $viewModel->setTemplate('application/index/thankobrigado');
        return $viewModel;
       
      }            
    } 
        
    // Pass form variable to view Caso os dados não passarem na validação
    return new ViewModel([
      'form' => $form
    ]);
  }



  // This action displays the Thank You page. The user is redirected to this
  // page on successful mail delivery. A parte do email deliveri foi comentada no método anterior
  public function thankYouAction()
  {
      return new ViewModel();
  }

  // This action displays the Send Error page. The user is redirected to this
  // page on mail delivery error.
  public function sendErrorAction() 
  {
    return new ViewModel();
  }


  // Fazendo uso do filtro de telefone diretamente
  // Digite na URL do navegador: http://localhost/zf3/estudozf3book/public/application/outputphonefilter
  // Para executar esta ação
  public function outputphonefilterAction()
  {
      // Create PhoneFilter filter.
      $filter = new PhoneFilter();

      // Configure the filter.
      $filter->setFormat(PhoneFilter::PHONE_FORMAT_INTL);

      // Filter a string.
      $filteredValue = $filter->filter('559832519244');

      //echo $filteredValue;

      // The expected filter's output is the '+55 (98) 3251-9244'.
      return new ViewModel(['phoneTranformed' => $filteredValue]);

    }


}

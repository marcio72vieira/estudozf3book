<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DownloadController extends AbstractActionController
{

    // Digite na barra de navegação do seu browse a linha abaixo, para baixar o arquivo amostra.txt localizado em data/download
    // http://localhost:8000/download/file?name=amostra.txt

    /**
     * This is the 'file' action that is invoked
     * when a user wants to download the given file.     
     */
    public function fileAction() 
    {
        // Get the file name from GET variable.Obtém o nome do arquivo passada na varipável name via GET (da URL), se nenhum 
        // nome for passado, será assumido espaço em branco
        $fileName = $this->params()->fromQuery('name', '');
        
        // Take some precautions to make file name secure. Toma algumas precações em relação ao nome do arquivo, garantindo 
        // segurança no nome do mesmo.
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
        
        // Try to open file. Tenta abrir o arquivo
        $path = './data/download/' . $fileName;

        // Se o arquivo não puder ser lido, responde com um Código de Status igual a 404, ou seja, o arquivo não foi encontrado
        // por isso não pode ser lido.
        if (!is_readable($path)) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);            
            return;
        }
        
        // Get file size in bytes. Obtém o tamanho do arquivo em bytes
        $fileSize = filesize($path);

        // Write HTTP headers. Escrevendo cabeçalhos de resposta (response)
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine(
                 "Content-type: application/octet-stream");
        $headers->addHeaderLine(
                 "Content-Disposition: attachment; filename=\"" . 
                $fileName . "\"");
        $headers->addHeaderLine("Content-length: $fileSize");
        $headers->addHeaderLine("Cache-control: private"); 
        
        // Write file content        
        $fileContent = file_get_contents($path);
        if($fileContent!=false) {                
            $response->setContent($fileContent);
        } else {        
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }
        
        // Return Response to avoid default view rendering
        return $this->getResponse();
    }
}

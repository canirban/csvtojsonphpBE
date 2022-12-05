<?php
require_once PROJECT_ROOT_PATH . "/Services/ConverterService.php";
class ConverterController
{

    public function postConversion()
    {

            $service=new ConverterService();
            $content=json_decode(file_get_contents('php://input'))->content;
            echo $service->converter($content);
         
    }

}?>
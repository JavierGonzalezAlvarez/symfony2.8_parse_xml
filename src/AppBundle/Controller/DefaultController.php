<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Services\CargarXml;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller

use \DOMDocument;

{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/cargar", name="cargar_fichero")
     */
    public function cargar_ficheroAction(Request $request) //, CargarXml $cargarxml)
    {                            
        //lanzar el servicio
        //$cargarxml = $this->get('cargarxml');        
        //$objects = $cargarxml->loadFile();
                

        //$form = $this->createFormBuilder($objects)            
        $form = $this->createFormBuilder()            
            ->add('ficheroxml', FileType::class, [ 'label' => 'Fichero XML'])            
            ->add('subir', SubmitType::class, array(
                'label' => 'Ejecutar',                                             
               
             /* 
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/xml',                            
                        ]])
                          ],
               */

                ))                       
            ->getForm();            

             //formulario recibido de la vista        
            $form->handleRequest($request);                                    

            if ($form->isValid()) {
            //if ($form->isSubmitted() && $form->isValid()) {
                // /** @var UploadedFile $file */
                $file = $form->get('ficheroxml')->getData();                                                
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);                           

                if ($file) {
                    //$cargarxml = $this->get('cargarxml');  
                    //$fileName = $cargarxml->loadFile($file);                                      
                    //dump($filename);
                    return $this->redirectToRoute('homepage'); 
                }              
            }                

        return $this->render('default/form_file.html.twig', [
            //'dato' => $datos,
            'formulario' => $form->createView(),
        ]);                        
    }


    /**
     * @Route("/leer_fichero", name="leer_fichero")
     */
    public function leerficheroAction(Request $request)
    {        
                     
        $nameFile = 'file:///home/javier/Desktop/cdv/parse_xml/app/Resources/Ficheros/RS_availability_no_soap.xml';        
        
        //$nameFile = file_get_contents('/../Resources/Ficheros/RS_availability.xml');                
        if (file_exists($nameFile)) {            
            $xml = simplexml_load_file($nameFile);                        
            $array_simple_xml = []; 
            
            $tiempo_inicial = microtime(true);

            $z = 0;
            for ($i = 0; $i < count($xml); $i++) {   

                $j = 0;        
                $z = count($xml->RoomStay[$i]->RoomRates[$j]->RoomRate);
                while ($j < $z) 
                {   
                        foreach ($xml->RoomStay[$i]->BasicPropertyInfo  as $hotel)                 
                        {                                                                       
                        $HotelCode = $hotel->attributes()->HotelCode;                                    
                        $array_simple_xml[] = $HotelCode;             
                        //print_r( $HotelCode ."</br>");
                        }                

                    foreach ($xml->RoomStay[$i]->RoomRates->RoomRate[$j]->Rates->Rate as $elemento1)                 
                        {   
                            $NombreHabitacion = "";                                                   
                            $NombreHabitacion = $elemento1->RateDescription->Text; 
                            $Precio = $elemento1->Total->attributes()->AmountAfterTax;                                                                  
                            $array_simple_xml[] = $NombreHabitacion;             
                            $array_simple_xml[] = $Precio;             
                            //print_r( $NombreHabitacion ."</br>");
                            //print_r( $Precio ."</br>");            
                        }                
                    
                        foreach ($xml->RoomStay[$i]->RoomRates->RoomRate[$j]->TPA_Extensions as $elemento2)                      
                        {                                                                                
                            $RegimenTarifa = $elemento2->Mealplan->attributes()->Category;                         
                            $array_simple_xml[] = $RegimenTarifa;                                         
                            //print_r( $RegimenTarifa ."</br>");
                        }                
                    
                        //print_r("padre -". $i. " hijo-". $j);                
                        //print_r( "<hr>");

                        $j++;   
                    }
            }        
            //var_dump($array_simple_xml);

            $tiempo_final = microtime(true);
            $tiempo = $tiempo_final - $tiempo_inicial;  
            //echo $tiempo. " segundos";

        } else {
            exit('No se ha cargo el fichero o no existe');
        }

        //recuperamos el servicio serializer
        //$serializer = $this->get('serializer');
        
        return $this->render('default/read_file.html.twig', array(            
            'data' => $array_simple_xml,
            'eficiencia' => $tiempo,                     
         ));                
        
    }

    /**
     * @Route("/leer_fichero_soap", name="leer_fichero_soap")
     */
    public function leerficheroSoapAction(Request $request)
    {        
        $nameFile = 'file:///home/javier/Desktop/cdv/parse_xml/app/Resources/Ficheros/RS_availability.xml';                   
        if (file_exists($nameFile)) {       

            $xml = simplexml_load_file($nameFile)                         
                ->children('http://schemas.xmlsoap.org/soap/envelope/')
                ->Body->children('http://www.opentravel.org/OTA/2003/05'); 
        
            //var_dump($xml);
            
            $tiempo_inicial = microtime(true);            
        
            //$array_simple_xml = []; 
                
            $z = 0;
            for ($i = 0; $i < count($xml->OTA_HotelAvailServiceResponse->OTA_HotelAvailRS->RoomStays->RoomStay); $i++) {   
                $j = 0;                        
                    foreach ($xml->OTA_HotelAvailServiceResponse->OTA_HotelAvailRS->RoomStays->RoomStay[$i]->BasicPropertyInfo  as $hotel)                 
                        {                                                                       
                        $HotelCode = $hotel->attributes()->HotelCode;                                    
                        //print_r( $HotelCode ."</br>");                        
                    }                  

                    $z = count($xml->OTA_HotelAvailServiceResponse->OTA_HotelAvailRS->RoomStays->RoomStay[$i]->RoomRates[$j]->RoomRate);                            
                        
                    while ($j < $z) 
                    {       
                        foreach ($xml->OTA_HotelAvailServiceResponse->OTA_HotelAvailRS->RoomStays->RoomStay[$i]->RoomRates->RoomRate[$j]->Rates->Rate as $elemento1)                 
                            {                                   
                                $NombreHabitacion = $elemento1->RateDescription->Text; 
                                $Precio = $elemento1->Total->attributes()->AmountAfterTax;                                                               
                                //print_r( $NombreHabitacion ."</br>");
                                //print_r( $Precio ."</br>");                                                                                                                                               
                            
                            }
                            $j++;   
                            
                            $array_simple_xml[$j] = array("Código Alojamiento ".$i, array( "Código"=> $HotelCode, "room_list" => $NombreHabitacion, "Total " => $Precio));        
                                                        
                    }        
                        
                    //Agrupar array por codigo de alojamiento

                    //

                    /*
                    foreach ($xml->OTA_HotelAvailServiceResponse->OTA_HotelAvailRS->RoomStays->RoomStay[$i]->RoomRates->RoomRate[$j]->TPA_Extensions as $elemento2)                      
                        {                                                                                
                            $RegimenTarifa = $elemento2->Mealplan->attributes()->Category;                         
                            $array_simple_xml[] = $RegimenTarifa;             
                            //$array_simple_xml["regimenTarifa"] = $RegimenTarifa;                            
                            print_r( $RegimenTarifa ."</br>");
                        }                                   
                     */                                                                             
            }        
        
                //var_dump($array_simple_xml);                      
                $tiempo_final = microtime(true);
                $tiempo = $tiempo_final - $tiempo_inicial;                    
        
        } else {
            exit('no se ha cargado el archivo');
        }

        
        return $this->render('default/read_file.html.twig', array(            
            'data' => $array_simple_xml,
            'eficiencia' => $tiempo,                     
         ));                
        
    }

    /**
     * @Route("/leer_fichero_dom", name="leer_fichero_dom")
     */
    public function leerficheroDomAction(Request $request)
    {                            
        $nameFile = 'file:///home/javier/Desktop/cdv/parse_xml/app/Resources/Ficheros/RS_availability.xml';                   
        if (file_exists($nameFile)) {                                      
                $dom = new \DOMDocument();        
                //$dom->formatOutput = true;    
                $dom->load("RS_availability.xml");            
                
                $nodeList_1 = $dom->getElementsByTagName('Text'); 
                $nodeList_2 = $dom->getElementsByTagName('BasicPropertyInfo');         
                $nodeList_3 = $dom->getElementsByTagName('Total'); 
                $nodeList_4 = $dom->getElementsByTagName('Mealplan'); 
            
                $contarRoomStay = $dom->getElementsByTagName('RoomStay'); 
                $contarRoomRates = $dom->getElementsByTagName('RoomRate'); 
                        
                echo $contarRoomStay->length;
                echo "<hr>"; 
                echo $contarRoomRates->length;
                echo "<hr>";                   
            
                foreach ($nodeList_1 as $node_1) {                   
                    print $node_1->nodeName . " = " . $node_1->nodeValue;  
                }     
                echo "<hr>";        
            
                foreach ($nodeList_2 as $node_2) {    
            
                    if($node_2->hasAttribute('HotelCode')) {                         
                        print $node_2->nodeName . " = " . $node_2->getAttribute('HotelCode');                                   
                    }           
                }
                echo "<hr>";  
                    
                foreach ($nodeList_3 as $node_3) {   
            
                    if($node_3->hasAttribute('AmountAfterTax')) {             
                        print $node_3->nodeName . " = " . $node_3->getAttribute('AmountAfterTax');                                               
                    }   
                }
                echo "<hr>";      
             
                foreach ($nodeList_4 as $node_4) {                                               
            
                    if($node_4->hasAttribute('Category')) {                                     
                        print $node_4->nodeName . " = " . $node_4->getAttribute('Category'); 
                                    
                    }                                        
                }
                echo "<hr>"; 
             
            
            
                $tiempo_inicial = microtime(true);
                $array_simple_xml = [];    
                         
            
            } else {
                exit('no se ha cargado el archivo');
            }
            

            $data = 1;
            $eficiencia = 1;

        return $this->render('default/read_file.html.twig', array(            
            'data' => $array_simple_xml,
            'eficiencia' => $tiempo,                     
         ));           
    }
    

}

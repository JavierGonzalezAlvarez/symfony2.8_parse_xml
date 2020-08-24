<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Services\CargarXml;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
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
        $cargarxml = $this->get('cargarxml');        
        $objects = $cargarxml->loadFile();

        $form = $this->createFormBuilder($objects)            
            ->add('ficheroxml', FileType::class)            
            ->add('subir', SubmitType::class, array(
                'label' => 'Subir fichero',
                /*
                'required' => false,                
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
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $brochureFile */
                //$brochureFile = $form->get('brochure')->getData();
                $brochureFile = $form->getData();
                if ($brochureFile) {
                    $brochureFileName = $cargarxml->upload($brochureFile);
                    $product->setBrochureFilename($brochureFileName);
                }
            }
    
        return $this->render('default/form_file.html.twig', [
              //'dato' => $datos,
            'formulario' => $form->createView(),
        ]);                
        

    }


}

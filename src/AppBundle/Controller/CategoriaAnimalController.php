<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Tcategoriaanimal;
use AppBundle\Services\Helpers;


class CategoriaAnimalController extends Controller{
    /* *
    * Función para agregar nueva categoría de animales
    * recive como aprametro un objeto JSON el  cual contiene la informacion necesaria para 
    * crear la nueva categoría. .
    * 
    */
    public function newAction(Request $request){
        $helpers = $this->get(Helpers::Class);
        //Recibir parámetros
        $json = $request->get('json',null);
        $params = json_decode($json);
       
        $data = array(
            'status' =>'error',
            'code' => 400,
            'msg' => 'Send data via post ¡¡',
            "json" =>$json
        );
        
        if($json != null){  
            
            $em = $this->getDoctrine()->getManager(); 
            // tomar los vores de los parametros que llegan por el bojeto Request         
            $pkidcategoriaanimal         = (isset($params->pkidcategoriaanimal)) ? $params->pkidcategoriaanimal : null;
            $nombrecategoruaanimal       = (isset($params->nombrecategoriaanimal)) ? $params->nombrecategoriaanimal : null;
            $descripcioncategoriaanimal  = (isset($params->descripcioncategoriaanimal)) ? $params->descripcioncategoriaanimal : null;
            $categoriaanimalactivo       = (isset($params->categoriaanimalactivo)) ? $params->categoriaanimalactivo : false;
            $codigocategoriaanimal       = (isset($params->codigocategoriaanimal)) ? $params->codigocategoriaanimal : null;
            $creacioncategoriaanimal     =new \Datetime("now"); 
            $modificacioncategoriaanimal =new \Datetime("now"); 
            
            // Verificar si hay  valores nulos
            if($nombrecategoruaanimal != null && $descripcioncategoriaanimal !=null && 
               $codigocategoriaanimal != null  ){
                $CA = new Tcategoriaanimal();
                $CA->setNombrecategoriaanimal($nombrecategoruaanimal);
                $CA->setDescripcioncategoriaanimal($descripcioncategoriaanimal);
                $CA->setCategoriaanimalactivo($categoriaanimalactivo);
                $CA->setCodigocategoriaanimal($codigocategoriaanimal);
                $CA->setCreacioncategoriaanimal($creacioncategoriaanimal);
                $CA->setModificacioncategoriaanimal($modificacioncategoriaanimal);                             
                
                // verificar si existe la categoría                
                $isset_CA = $em->getRepository('BackendBundle:Tcategoriaanimal')->findBy(
                                                array("pkidcategoriaanimal" => $pkidcategoriaanimal));

                if (count($isset_CA) == 0){
                    // si no existe, crea la categoría
                    $em->persist($CA);
                    $em->flush();
                    $data = array(
                    'status' =>'success',
                    'code' => 200,
                    'msg' => 'Categoria Animal Creada ¡¡',
                    'Categoriaanimal' => $CA                    
                    );
                }else{
                    // error cuando envian parametros nulos 
                    $data = array(
                    'status' =>'error2',
                    'code' => 400,
                    'msg' => 'Categoria Animal no creada, Datos nulos o herroneos  ¡¡',
                    "json" =>$json
                    );
                }

                
            }
        }
        return $helpers->json($data);
    }
    public function editAction(Request $request){
        $helpers = $this->get(Helpers::Class);
        
        $em = $this->getDoctrine()->getManager();
        // Recibe el objeto Json  
        $json = $request->get('json',null);
        $params = json_decode($json);
        $data = array(
            'status' =>'error1',
            'code' => 400,
            'msg' => 'Categoría no actualizada ¡¡',
            "json" =>$json
        );
        // verifica si el objeto json contiene datos
        if($json != null){
            // tomar los datos que le llegan via post                    
            $pkidcategoriaanimal         = (isset($params->pkidcategoriaanimal)) ? $params->pkidcategoriaanimal : null;
            $nombrecategoruaanimal       = (isset($params->nombrecategoriaanimal)) ? $params->nombrecategoriaanimal : null;
            $descripcioncategoriaanimal  = (isset($params->descripcioncategoriaanimal)) ? $params->descripcioncategoriaanimal : null;
            $categoriaanimalactivo       = (isset($params->categoriaanimalactivo)) ? $params->categoriaanimalactivo : null;
            $codigocategoriaanimal       = (isset($params->codigocategoriaanimal)) ? $params->codigocategoriaanimal : null;
            $modificacioncategoriaanimal =new \Datetime("now"); 
            // consultar si la categoría a editar
            $CA = $em->getRepository('BackendBundle:Tcategoriaanimal')->findOneBy(
                array("pkidcategoriaanimal" => (int)$pkidcategoriaanimal));
           
            if($nombrecategoruaanimal != null && $descripcioncategoriaanimal !=null 
                 &&  $codigocategoriaanimal != null &&count($CA) != 0  ){
                // si los datos no son nulos  y la categoria existe se asigna los nuevos datos a la categoría   
                $CA->setNombrecategoriaanimal($nombrecategoruaanimal);
                $CA->setDescripcioncategoriaanimal($descripcioncategoriaanimal);
                $CA->setCategoriaanimalactivo($categoriaanimalactivo);
                $CA->setCodigocategoriaanimal($codigocategoriaanimal);
                $CA->setModificacioncategoriaanimal($modificacioncategoriaanimal);                            
                
                $em->persist($CA);
                $em->flush();
                $data = array(
                'status' =>'success',
                'code' => 200,
                'msg' => 'Categoria Animal Actualizada ¡¡',
                'Categoriaanimal' => $CA                    
                );
                
            }
        } else {
            $data = array(
                'status' =>'error1',
                'code' => 400,
                'msg' => 'Categoria Animal no creada,Enviar datos json  ¡¡',
                "json" =>$json
                );
        }
                     
        return $helpers->json($data);
    }
     public function deleteAction(Request $request)
    {
        $helpers = $this->get(Helpers::Class);
              
        $em = $this->getDoctrine()->getManager();
        $json = $request->get('json',null);
        $params = json_decode($json);
        $data = array("status" => "error","code"=>404,"msg"=>"Enviar datos");
        
        if ($json =!  null) {
            $pkidcategoriaanimal         = (isset($params->pkidcategoriaanimal)) ? $params->pkidcategoriaanimal : null;
            if($codigocategoriaanimal =! null){
                $CA = $em->getRepository('BackendBundle:Tcategoriaanimal')->findOneBy(array("pkidcategoriaanimal"=>(int)$pkidcategoriaanimal));
                          
                if($CA && is_object($CA)){
                    $em->remove($CA);
                    $em->flush();
                    $data = array("status" => "success","code"=>200,"data"=>$CA);
                }else{
                    $data = array("status" => "error","code"=>404,"msg"=>"Categoria animal no encontrada");
                }
            } else {
                $data = array("status" => "error","code"=>404,"msg"=>"Parámetro no válido");
 
            } 

            
        }
                             
        return $helpers->json($data);
    }

    public function queryAction(Request $request){
       
        $helpers = $this->get(Helpers::Class);
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('BackendBundle:Tcategoriaanimal')->findAll();   

       if(count($categorias) == 0){
           $data = array("status" => "success","code"=>200,"msg"=>"No se encontraron resultados");
       }
       else{
           $data = array("status" => "success","code"=>200,"data"=>$categorias);
       }
       return $helpers->json($data);
   }

}

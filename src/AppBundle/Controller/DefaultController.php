<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Form\Extension\Core\Type\DateTime;
use \DateTime;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use AppBundle\Entity\CiqT;
use AppBundle\Entity\Document;


class DefaultController extends Controller
{
    /**
     * @Route("/44", name="homepage")
     */
    public function indexAction(Request $request)
    {
       $Document = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
        $row = 0;
       // $path=$this->get('kernel')->getRootDir(); // Représente la ligne
        // Import du fichier CSV 
        if (($handle = fopen("/var/www/html/projet/src/AppBundle/Resources/file.csv", "r")) !== FALSE) { // Lecture du fichier, à adapter
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                $num = count($data); // Nombre d'éléments sur la ligne traitée
                $row++;
               
                for ($c = 0; $c < $num; $c++) {
                    $Document[$row] = array(
                            "Tech" => $data[0],
                            "SiteCode" => $data[1],
                            "Integration_type" => $data[2],
                            "Source_Vendor" => $data[3],
                            "Region" => $data[4],
                            "Target_Cell" => $data[5],
                            "Date_Allumage" => $data[6]
                    );
                }
            }
            fclose($handle); 
            
        }        
        
         

        $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
        
        // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
        foreach ($Document as $doc) {
            
            // On crée un objet utilisateur
            $ciqt = new Ciqt();
    
       
            
            // Hydrate l'objet avec les informations provenants du fichier CSV
            $ciqt->setTechF($doc["Tech"]);
            $ciqt->setSiteCodeF($doc["SiteCode"]);
            $ciqt->setIntegrationTypeF($doc["Integration_type"]);
            $ciqt->setSourceVendorF($doc["Source_Vendor"]);
            $ciqt->setRegionF($doc["Region"]);
            $ciqt->setTargetCellNameF($doc["Target_Cell"]);
            $dateString = $doc["Date_Allumage"];
              
           

            $Date = new DateTime($dateString);
            
            $ciqt->setDateAllumageF($Date);

                
            // Enregistrement de l'objet en vu de son écriture dans la base de données
            $em->persist($ciqt);
            
        }
        
        // Ecriture dans la base de données
        $em->flush();
        
        // Renvoi la réponse (ici affiche un simple OK pour l'exemple)
        return new Response('OK');
    }
      
    
}

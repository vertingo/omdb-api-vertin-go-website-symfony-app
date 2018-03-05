<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use \DateTime;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
//use AppBundle\Entity\Document;
use AppBundle\Entity\CiqT;
use AppBundle\Entity\operation_t;
use AppBundle\Entity\Films;
use AppBundle\Omdbapi\Omdbapi;

class ExportCsvController extends Controller
{
    
  

    /**
     * @Route("/export", name="Csv")
     */
    public  function indexAction()
    {

      $pdf = new \FPDF();

      $resultats = $this->getDoctrine()
      ->getRepository('AppBundle:Films')
      ->findAll();

        foreach($resultats as $rows) 
        {
          $pdf->AddPage();
          $pdf->Image('http://vertin-go.com/TopSite/Icon/pub_cible.jpg',0,0,20,0,'','http://vertin-go.com/TopSite/accueil.php');
          $title = 'Exportation de vos films au format PDF';
          $pdf->SetTitle($title);
          $pdf->SetFont('Arial','B',16);
          $pdf->SetTextColor(0, 0, 0);
          $pdf->Multicell(190, 5, $rows->getTitreFilm(), '', 'C', false);
          $pdf->Image($rows->getIllustration(),30,null,150,83);
          $pdf->Multicell(190, 5, "Annee de production: ".$rows->getAnnee(), '', 'C', false);
          $pdf->Multicell(190, 5, "Classification: ".$rows->getClassification(), '', 'C', false);
          $pdf->Multicell(190, 5, "Date de sortie: ".$rows->getSortie(), '', 'C', false);
          $pdf->Multicell(190, 5, "Duree: ".$rows->getDuree()." minutes!", '', 'C', false);
          $pdf->Multicell(190, 5, "Genre: ".$rows->getGenre(), '', 'C', false);
          $pdf->Multicell(190, 5, "Directeur: ".$rows->getDirecteur(), '', 'C', false);
          $pdf->Multicell(190, 5, "Scenaristes: ".$rows->getScenariste(), '', 'C', false);
          $pdf->Multicell(190, 5, "Acteurs: ".$rows->getActeurs(), '', 'C', false);
          $pdf->Multicell(190, 5, "Synopsis: ".$rows->getSynopsis(), '', 'C', false);
          $pdf->Multicell(190, 5, "Nationalite: ".$rows->getNationalite(), '', 'C', false);
          $pdf->Multicell(190, 5, "Recompenses: ".$rows->getRecompense(), '', 'C', false);
          $pdf->Multicell(190, 5, "Notations: ".$rows->getNotations(), '', 'C', false);
          $pdf->Multicell(190, 5, "Metascore: ".$rows->getMetascore(), '', 'C', false);
          $pdf->Multicell(190, 5, "Imdb Notation: ".$rows->getImdbNotation(), '', 'C', false);
          $pdf->Multicell(190, 5, "Imdb Votes: ".$rows->getImdbVotes(), '', 'C', false);
          $pdf->Multicell(190, 5, "Date de sortie Dvd: ".$rows->getDvd(), '', 'C', false);
          $pdf->Multicell(190, 5, "Production: ".$rows->getProduction(), '', 'C', false);
          $pdf->Multicell(190, 5, "Site Web: ".$rows->getWebsite(), '', 'C', false);
        }

      return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));
    }


/**
     * @Route("/import", name="import_csv2")
     * @Method("POST")
     */
    public  function importAction(Request $request)
    {    

      //form upload///////////////////////////////

      if(isset($_FILES['csv']))
      {       
        $dossier = 'C:/xampp/htdocs/Omdb_Api_Vertin_Go_Website/src/AppBundle/Resources/';
        $fichier = basename($_FILES['csv']['name']);

        $result = move_uploaded_file($_FILES['csv']['tmp_name'], $dossier . $fichier);

        if($result)
        {
        $Document = array(); //Tableau qui va contenir les éléments extraits du fichier CSV
        $row = 0;
        //$path=$this->get('kernel')->getRootDir(); // Représente la ligne

        // Import du fichier CSV 
        if(($handle = fopen("C:/xampp/htdocs/Omdb_Api_Vertin_Go_Website/src/AppBundle/Resources/".$fichier, "r"))!==FALSE) 
        { // Lecture du fichier, à adapter
            
            $data=array();
            while(($data = fgetcsv($handle, 1000,":"))!==false) 
            { // Eléments séparés par un point-virgule, à modifier si necessaire
                $num = count($data); // Nombre d'éléments sur la ligne traitée

                echo $num;
                /***************************************add/update****************************************************************/
                //si add ou update
                
                if($data[0]=="add")
                {
                  //si add ou update
                  $repository = $this->getDoctrine()
                  ->getRepository(Films::class);
                  $query = $repository->createQueryBuilder('p')
                  ->where('p.titreFilm = :titre_film')
                  ->setParameter('titre_film', $data[1])
                  ->getQuery();

                  $films = $query->getResult();

                  $Omdbapi = new Omdbapi(['tomatoes' => FALSE ,'plot' => 'full','apikey' => '63154a5b']);
                  $omdbapi_result = $Omdbapi->get_by_title($data[1]);
                  
                  //update
                  if($films) 
                  {
                    
                      $em = $this->getDoctrine()->getManager();
                      $edit = $em->getRepository(Films::class)->findOneBy(['titreFilm' => $data[1]]);

                      $edit->setTitreFilm($omdbapi_result["Title"]);
                      $edit->setAnnee($omdbapi_result["Year"]);
                      $edit->setClassification($omdbapi_result["Rated"]);
                      $edit->setSortie($omdbapi_result["Released"]);
                      $edit->setDuree($omdbapi_result["Runtime"]);
                      
                      $i=0;
                      $genre="";

                      if(is_array($omdbapi_result["Genre"]))
                      {
                        while($i<count($omdbapi_result["Genre"]))
                        {
                        $genre.=$omdbapi_result["Genre"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $genre=$omdbapi_result["Genre"];
                      }
                      

                      $edit->setGenre($genre);
                      $edit->setDirecteur($omdbapi_result["Director"]);
                      
                      $i=0;
                      $writer="";

                      if(is_array($omdbapi_result["Writer"]))
                      {
                        while($i<count($omdbapi_result["Writer"]))
                        {
                        $writer.=$omdbapi_result["Writer"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $writer=$omdbapi_result["Writer"];
                      }
                      

                      $edit->setScenariste($writer);
 
                      $i=0;
                      $actors="";

                      if(is_array($omdbapi_result["Actors"]))
                      {
                        while($i<count($omdbapi_result["Actors"]))
                        {
                        $actors.=$omdbapi_result["Actors"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $actors=$omdbapi_result["Actors"];
                      }

                      $edit->setActeurs($actors);
                      $edit->setSynopsis($omdbapi_result["Plot"]);
                      
                      $i=0;
                      $language="";

                      if(is_array($omdbapi_result["Language"]))
                      {
                        while($i<count($omdbapi_result["Language"]))
                        {
                        $language.=$omdbapi_result["Language"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $language=$omdbapi_result["Language"];
                      }

                      $edit->setLangue($language);

                      $i=0;
                      $country="";

                      if(is_array($omdbapi_result["Country"]))
                      {
                        while($i<count($omdbapi_result["Country"]))
                        {
                        $country.=$omdbapi_result["Country"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $country=$omdbapi_result["Country"];
                      }
                      

                      $edit->setNationalite($country);
                      $edit->setRecompense($omdbapi_result["Awards"]);
                      $edit->setIllustration($omdbapi_result["Poster"]);
                      
                      $i=0;
                      $j=0;
                      $ratings="";
                      while($i<count($omdbapi_result["Ratings"]))
                      {
                          while($j<count($omdbapi_result["Ratings"][$i]))
                          {
                              if($j==0)
                              {
                                  $ratings.=$omdbapi_result["Ratings"][$i]["Source"].", ";
                              }
                              else
                              {
                                  $ratings.=$omdbapi_result["Ratings"][$i]["Value"].", ";
                              }
                          $j++;
                          }
                        $i++;
                      }

                      $edit->setNotations($ratings);
                      $edit->setMetascore($omdbapi_result["Metascore"]);
                      $edit->setImdbNotation($omdbapi_result["imdbRating"]);
                      $edit->setImdbVotes($omdbapi_result["imdbVotes"]);
                      $edit->setImdbId($omdbapi_result["imdbID"]);
                      $edit->setType($omdbapi_result["Type"]);
                      $edit->setDvd($omdbapi_result["DVD"]);
                      $edit->setBoxoffice($omdbapi_result["BoxOffice"]);
                      $edit->setProduction($omdbapi_result["Production"]);
                      $edit->setWebsite($omdbapi_result["Website"]);
                      $edit->setReponse($omdbapi_result["Response"]);
                        
                      $em->flush();
                  }
                  elseif(!$films) 
                  {     
                     
                      $i=0;
                      $genre="";

                      if(is_array($omdbapi_result["Genre"]))
                      {
                        while($i<count($omdbapi_result["Genre"]))
                        {
                        $genre.=$omdbapi_result["Genre"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $genre=$omdbapi_result["Genre"];
                      }
                      
                      $i=0;
                      $writer="";

                      if(is_array($omdbapi_result["Writer"]))
                      {
                        while($i<count($omdbapi_result["Writer"]))
                        {
                        $writer.=$omdbapi_result["Writer"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $writer=$omdbapi_result["Writer"];
                      }
                      

                      $i=0;
                      $actors="";

                      if(is_array($omdbapi_result["Actors"]))
                      {
                        while($i<count($omdbapi_result["Actors"]))
                        {
                        $actors.=$omdbapi_result["Actors"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                      $actors=$omdbapi_result["Actors"];
                      }

                      $i=0;
                      $language="";

                      if(is_array($omdbapi_result["Language"]))
                      {
                        while($i<count($omdbapi_result["Language"]))
                        {
                        $language.=$omdbapi_result["Language"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                        $language=$omdbapi_result["Language"];
                      }

                      $i=0;
                      $country="";

                      if(is_array($omdbapi_result["Country"]))
                      {
                        while($i<count($omdbapi_result["Country"]))
                        {
                        $country.=$omdbapi_result["Country"][$i].", ";
                        $i++;
                        }
                      }
                      else
                      {
                        $country=$omdbapi_result["Country"];
                      }

                      $i=0;
                      $j=0;
                      $ratings="";
                      while($i<count($omdbapi_result["Ratings"]))
                      {
                          while($j<count($omdbapi_result["Ratings"][$i]))
                          {
                              if($j==0)
                              {
                                  $ratings.=$omdbapi_result["Ratings"][$i]["Source"].", ";
                              }
                              else
                              {
                                  $ratings.=$omdbapi_result["Ratings"][$i]["Value"].", ";
                              }
                          $j++;
                          }
                        $i++;
                      }

                    $boxoffice="";
                    
                    if($omdbapi_result["BoxOffice"]==null)
                    {
                      $boxoffice="N/A";
                    }

                    $website="";
                    
                    if($omdbapi_result["Website"]==null)
                    {
                      $website="N/A";
                    }

                     $row++;
                     for ($c = 0; $c < $num; $c++) {
                      $Document[$row] = array(
                        "Titre" => $omdbapi_result["Title"],
                        "Annee" => $omdbapi_result["Year"],
                        "Classification" => $omdbapi_result["Rated"],
                        "Sortie" => $omdbapi_result["Released"],
                        "Duree" => $omdbapi_result["Runtime"],
                        "Genre" => $genre,
                        "Directeur" => $omdbapi_result["Director"],
                        "Scenariste" => $writer,
                        "Acteurs" => $actors,
                        "Synopsis" => $omdbapi_result["Plot"],
                        "Langue" => $language,
                        "Nationalite" => $country,
                        "Recompense" => $omdbapi_result["Awards"],
                        "Illustration" => $omdbapi_result["Poster"],
                        "Notations" => $ratings,
                        "Metascore" => $omdbapi_result["Metascore"],
                        "Imdb_notation" => $omdbapi_result["imdbRating"],
                        "Imdb_votes" => $omdbapi_result["imdbVotes"],
                        "Imdb_id" => $omdbapi_result["imdbID"],
                        "Type" => $omdbapi_result["Type"],
                        "Dvd" => $omdbapi_result["DVD"],
                        "Boxoffice" => $boxoffice,
                        "Production" => $omdbapi_result["Production"],
                        "Website" => $website,
                        "Reponse" => $omdbapi_result["Response"]
                      );
                    }
                }             
              }

            

              if($data[0] == "delete")
              {
               $deleteQuery = $this->getDoctrine()
               ->getManager()
               ->createQueryBuilder('d')
               ->delete('AppBundle:Films', 'd')
               ->where('d.titreFilm = :titre_film')->setParameter('titre_film', $data[0])->getQuery();
               $deleted = $deleteQuery->getResult();
              }

           }
           fclose($handle); 
         }  

        $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
        
        // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
        foreach($Document as $doc) 
        {
          // On crée un objet films
          $Films = new Films();

          // Hydrate l'objet avec les informations provenants du fichier CSV
          $Films->setTitreFilm($doc["Titre"]);
          $Films->setAnnee($doc["Annee"]);
          $Films->setClassification($doc["Classification"]);
          $Films->setSortie($doc["Sortie"]);
          $Films->setDuree($doc["Duree"]);
          $Films->setGenre($doc["Genre"]);
          $Films->setDirecteur($doc["Directeur"]);
          $Films->setScenariste($doc["Scenariste"]);
          $Films->setActeurs($doc["Acteurs"]);
          $Films->setSynopsis($doc["Synopsis"]);
          $Films->setLangue($doc["Langue"]);
          $Films->setNationalite($doc["Nationalite"]);
          $Films->setRecompense($doc["Recompense"]);
          $Films->setIllustration($doc["Illustration"]);
          $Films->setNotations($doc["Notations"]);
          $Films->setMetascore($doc["Metascore"]);
          $Films->setImdbNotation($doc["Imdb_notation"]);
          $Films->setImdbVotes($doc["Imdb_votes"]);
          $Films->setImdbId($doc["Imdb_id"]);
          $Films->setType($doc["Type"]);
          $Films->setDvd($doc["Dvd"]);
          $Films->setBoxoffice($doc["Boxoffice"]);
          $Films->setProduction($doc["Production"]);
          $Films->setWebsite($doc["Website"]);
          $Films->setReponse($doc["Reponse"]);
          
          // $dateString = $doc["Date_Allumage"];
          // $Date = new DateTime($dateString);
          // $Films->setDateAllumageF($Date);

          // Enregistrement de l'objet en vu de son écriture dans la base de données
          $em->persist($Films);

        }
        
        // Ecriture dans la base de données
        $em->flush();
        
        // Renvoi la réponse (ici affiche un simple OK pour l'exemple)
        return $this->redirectToRoute('films');
    } //Si la fonction renvoie TRUE, c'est que la a fonctionne
  }
}


}
<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\Films;

/**
 * Films controller.
 *
 */
class FilmsController extends Controller
{
    /**
     * Lists all Films entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Films')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($films, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('films/index.html.twig', array(
            'films' => $films,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString

        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('AppBundle\Form\FilmsFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('FilmsControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->handleRequest($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('FilmsControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('FilmsControllerFilter')) {
                $filterData = $session->get('FilmsControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('AppBundle\Form\FilmsFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }


    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('films', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }
    
    
    
    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request) {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->groupBy('e.id')->getQuery()->getOneOrNullResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }
    
    

    /**
     * Displays a form to create a new Films entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $film = new Films();
        $form   = $this->createForm('AppBundle\Form\FilmsType', $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($film);
            $em->flush();
            
            $editLink = $this->generateUrl('films_edit', array('id' => $film->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New film was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'films' : 'films_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('films/new.html.twig', array(
            'film' => $film,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Films entity.
     *
     */
    public function showAction(Films $film)
    {
        $deleteForm = $this->createDeleteForm($film);
        return $this->render('films/show.html.twig', array(
            'film' => $film,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Films entity.
     *
     */
    public function editAction(Request $request, Films $film)
    {
        $deleteForm = $this->createDeleteForm($film);
        $editForm = $this->createForm('AppBundle\Form\FilmsType', $film);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($film);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('films_edit', array('id' => $film->getId()));
        }
        return $this->render('films/edit.html.twig', array(
            'film' => $film,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Films entity.
     *
     */
    public function deleteAction(Request $request, Films $film)
    {
    
        $form = $this->createDeleteForm($film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($film);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Films was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Films');
        }
        
        return $this->redirectToRoute('films');
    }
    
    /**
     * Creates a form to delete a Films entity.
     *
     * @param Films $film The Films entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Films $film)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('films_delete', array('id' => $film->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Films by id
     *
     */
    public function deleteByIdAction(Films $film){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($film);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Films was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Films');
        }

        return $this->redirect($this->generateUrl('films'));

    }
    

    /**
    * Bulk Action
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Films');

                foreach ($ids as $id) {
                    $film = $repository->find($id);
                    $em->remove($film);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'films was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the films ');
            }
        }

        return $this->redirect($this->generateUrl('films'));
    }

    
    public  function addFilmsAction(Request $request)
    {    
                $Document = array(); //Tableau qui va contenir les éléments extraits du fichier CSV
                $row = 0;
                //$path=$this->get('kernel')->getRootDir(); // Représente la ligne

                /***************************************add/update****************************************************************/
                
                //si add ou update
                $repository = $this->getDoctrine()
                ->getRepository(Films::class);
                $query = $repository->createQueryBuilder('p')
                ->where('p.titreFilm = :titre_film')
                ->setParameter('titre_film', "batman")
                ->getQuery();

                $films = $query->getResult();

                $Omdbapi = new Omdbapi(['tomatoes' => FALSE ,'plot' => 'full','apikey' => '63154a5b']);
                $omdbapi_result = $Omdbapi->get_by_title("batman");
                  
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
          
          // Enregistrement de l'objet en vu de son écriture dans la base de données
          $em->persist($Films);

        }
        
        // Ecriture dans la base de données
        $em->flush();
        
        // Renvoi la réponse (ici affiche un simple OK pour l'exemple)
        return $this->redirectToRoute('films');
     //Si la fonction renvoie TRUE, c'est que la a fonctionne
  
   }

}

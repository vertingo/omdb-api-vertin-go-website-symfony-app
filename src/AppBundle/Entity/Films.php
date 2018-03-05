<?php

namespace AppBundle\Entity;

/**
 * Films
 */
class Films
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $titreFilm;

    /**
     * @var string
     */
    private $annee;

    /**
     * @var string
     */
    private $classification;

    /**
     * @var string
     */
    private $sortie;

    /**
     * @var string
     */
    private $duree;

    /**
     * @var string
     */
    private $genre;

    /**
     * @var string
     */
    private $directeur;

    /**
     * @var string
     */
    private $scenariste;

    /**
     * @var string
     */
    private $acteurs;

    /**
     * @var string
     */
    private $synopsis;

    /**
     * @var string
     */
    private $langue;

    /**
     * @var string
     */
    private $nationalite;

    /**
     * @var string
     */
    private $recompense;

    /**
     * @var string
     */
    private $illustration;

    /**
     * @var string
     */
    private $notations;

    /**
     * @var string
     */
    private $metascore;

    /**
     * @var string
     */
    private $imdbNotation;

    /**
     * @var string
     */
    private $imdbVotes;

    /**
     * @var int
     */
    private $imdbId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $dvd;

    /**
     * @var string
     */
    private $boxoffice;

    /**
     * @var string
     */
    private $production;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $reponse;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titreFilm
     *
     * @param string $titreFilm
     *
     * @return Films
     */
    public function setTitreFilm($titreFilm)
    {
        $this->titreFilm = $titreFilm;

        return $this;
    }

    /**
     * Get titreFilm
     *
     * @return string
     */
    public function getTitreFilm()
    {
        return $this->titreFilm;
    }

    /**
     * Set annee
     *
     * @param string $annee
     *
     * @return Films
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set classification
     *
     * @param string $classification
     *
     * @return Films
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return string
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set sortie
     *
     * @param string $sortie
     *
     * @return Films
     */
    public function setSortie($sortie)
    {
        $this->sortie = $sortie;

        return $this;
    }

    /**
     * Get sortie
     *
     * @return string
     */
    public function getSortie()
    {
        return $this->sortie;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Films
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Films
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set directeur
     *
     * @param string $directeur
     *
     * @return Films
     */
    public function setDirecteur($directeur)
    {
        $this->directeur = $directeur;

        return $this;
    }

    /**
     * Get directeur
     *
     * @return string
     */
    public function getDirecteur()
    {
        return $this->directeur;
    }

    /**
     * Set scenariste
     *
     * @param string $scenariste
     *
     * @return Films
     */
    public function setScenariste($scenariste)
    {
        $this->scenariste = $scenariste;

        return $this;
    }

    /**
     * Get scenariste
     *
     * @return string
     */
    public function getScenariste()
    {
        return $this->scenariste;
    }

    /**
     * Set acteurs
     *
     * @param string $acteurs
     *
     * @return Films
     */
    public function setActeurs($acteurs)
    {
        $this->acteurs = $acteurs;

        return $this;
    }

    /**
     * Get acteurs
     *
     * @return string
     */
    public function getActeurs()
    {
        return $this->acteurs;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return Films
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set langue
     *
     * @param string $langue
     *
     * @return Films
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set nationalite
     *
     * @param string $nationalite
     *
     * @return Films
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set recompense
     *
     * @param string $recompense
     *
     * @return Films
     */
    public function setRecompense($recompense)
    {
        $this->recompense = $recompense;

        return $this;
    }

    /**
     * Get recompense
     *
     * @return string
     */
    public function getRecompense()
    {
        return $this->recompense;
    }

    /**
     * Set illustration
     *
     * @param string $illustration
     *
     * @return Films
     */
    public function setIllustration($illustration)
    {
        $this->illustration = $illustration;

        return $this;
    }

    /**
     * Get illustration
     *
     * @return string
     */
    public function getIllustration()
    {
        return $this->illustration;
    }

    /**
     * Set notations
     *
     * @param string $notations
     *
     * @return Films
     */
    public function setNotations($notations)
    {
        $this->notations = $notations;

        return $this;
    }

    /**
     * Get notations
     *
     * @return string
     */
    public function getNotations()
    {
        return $this->notations;
    }

    /**
     * Set metascore
     *
     * @param string $metascore
     *
     * @return Films
     */
    public function setMetascore($metascore)
    {
        $this->metascore = $metascore;

        return $this;
    }

    /**
     * Get metascore
     *
     * @return string
     */
    public function getMetascore()
    {
        return $this->metascore;
    }

    /**
     * Set imdbNotation
     *
     * @param string $imdbNotation
     *
     * @return Films
     */
    public function setImdbNotation($imdbNotation)
    {
        $this->imdbNotation = $imdbNotation;

        return $this;
    }

    /**
     * Get imdbNotation
     *
     * @return string
     */
    public function getImdbNotation()
    {
        return $this->imdbNotation;
    }

    /**
     * Set imdbVotes
     *
     * @param string $imdbVotes
     *
     * @return Films
     */
    public function setImdbVotes($imdbVotes)
    {
        $this->imdbVotes = $imdbVotes;

        return $this;
    }

    /**
     * Get imdbVotes
     *
     * @return string
     */
    public function getImdbVotes()
    {
        return $this->imdbVotes;
    }

    /**
     * Set imdbId
     *
     * @param integer $imdbId
     *
     * @return Films
     */
    public function setImdbId($imdbId)
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    /**
     * Get imdbId
     *
     * @return int
     */
    public function getImdbId()
    {
        return $this->imdbId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Films
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dvd
     *
     * @param string $dvd
     *
     * @return Films
     */
    public function setDvd($dvd)
    {
        $this->dvd = $dvd;

        return $this;
    }

    /**
     * Get dvd
     *
     * @return string
     */
    public function getDvd()
    {
        return $this->dvd;
    }

    /**
     * Set boxoffice
     *
     * @param string $boxoffice
     *
     * @return Films
     */
    public function setBoxoffice($boxoffice)
    {
        $this->boxoffice = $boxoffice;

        return $this;
    }

    /**
     * Get boxoffice
     *
     * @return string
     */
    public function getBoxoffice()
    {
        return $this->boxoffice;
    }

    /**
     * Set production
     *
     * @param string $production
     *
     * @return Films
     */
    public function setProduction($production)
    {
        $this->production = $production;

        return $this;
    }

    /**
     * Get production
     *
     * @return string
     */
    public function getProduction()
    {
        return $this->production;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Films
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     *
     * @return Films
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }
}


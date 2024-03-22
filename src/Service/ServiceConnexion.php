<?php

namespace App\Trellotrolle\Service;

use App\Trellotrolle\Controleur\ControleurUtilisateur;
use App\Trellotrolle\Lib\ConnexionUtilisateur;
use App\Trellotrolle\Lib\MessageFlash;
use App\Trellotrolle\Lib\MotDePasse;
use App\Trellotrolle\Modele\DataObject\Utilisateur;
use App\Trellotrolle\Modele\HTTP\Cookie;
use App\Trellotrolle\Modele\Repository\UtilisateurRepository;
use App\Trellotrolle\Service\Exception\ConnexionException;
use App\Trellotrolle\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class ServiceConnexion implements ServiceConnexionInterface
{


    public function __construct(private UtilisateurRepository $utilisateurRepository)
    {
    }

    /**
     * @throws ConnexionException
     */
    public function pasConnecter()
    {
        /*if (!ConnexionUtilisateur::estConnecte()) {
            throw new ConnexionException("Veuillez vous connecter", Response::HTTP_FORBIDDEN);
        }*/
    }

    /**
     * @throws ConnexionException
     */
    public function dejaConnecter()
    {
        if (ConnexionUtilisateur::estConnecte()) {
            throw new ConnexionException("Vous êtes déjà connecter");
        }
    }

    /**
     * @throws ServiceException
     */
    public function deconnecter()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            throw new ConnexionException("Utilisateur non connecté");
        }
        ConnexionUtilisateur::deconnecter();
    }

    /**
     * @throws ServiceException
     */
    public function connecter($login, $mdp)
    {
        if (is_null($login) || is_null($mdp)) {
            //TODO ce messageFlash était en "danger", c'est maintenant un "warning"
            throw new ServiceException("Login ou mot de passe manquant");
        }

        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur == null) {
            throw new ServiceException("Login inconnu.");
        }

        if (!MotDePasse::verifier($mdp, $utilisateur->getMdpHache())) {
            throw new ServiceException("Mot de passe incorrect.");
        }

        ConnexionUtilisateur::connecter($utilisateur->getLogin());
        Cookie::enregistrer("login", $login);
        Cookie::enregistrer("mdp", $mdp);
    }
}
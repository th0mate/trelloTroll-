<?php

namespace App\Trellotrolle\Tests;

use App\Trellotrolle\Modele\DataObject\Utilisateur;
use App\Trellotrolle\Modele\Repository\ConnexionBaseDeDonnees;
use App\Trellotrolle\Modele\Repository\ConnexionBaseDeDonneesInterface;
use App\Trellotrolle\Modele\Repository\UtilisateurRepository;
use App\Trellotrolle\Modele\Repository\UtilisateurRepositoryInterface;
use PHPUnit\Framework\TestCase;
class UtilisateurRepositoryTest extends TestCase
{


    private static UtilisateurRepositoryInterface  $utilisateurRepository;

    private static ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$connexionBaseDeDonnees = new ConnexionBaseDeDonnees(new ConfigurationBDDTestUnitaires());
        self::$utilisateurRepository = new UtilisateurRepository(self::$connexionBaseDeDonnees);
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO
                                                              utilisateur (login,nom,prenom,email,mdphache,nonce)
                                                              VALUES ('bob69','bobby','bob','bob.bobby@bob.com','mdpBob','aaa')");
        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO 
                                                              utilisateur (login,nom,prenom,email,mdphache,nonce)
                                                              VALUES ('bib420','bibby','bib','bib.bibby@bob.com','mdpBib','aaa')");
        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO 
                                                              utilisateur (login,nom,prenom,email,mdphache,nonce)
                                                              VALUES ('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp','aaa')");
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        self::$connexionBaseDeDonnees->getPdo()->query("DELETE FROM utilisateur");
    }

    /**Test recupererUtilisateursParEmail, prends : string $email retourne array*/

    public function testRecupererUtilisateursParEmailExistant(){
        $fakeUtilisateur= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob');
        $array = [$fakeUtilisateur];
        $this->assertEquals($array, self::$utilisateurRepository->recupererUtilisateursParEmail('bob.bobby@bob.com'));
    }

    public function testRecupererUtilisateursParEmailNonExistant(){
        $this->assertEquals([], self::$utilisateurRepository->recupererUtilisateursParEmail('george.george@george.com'));
    }

    /**Test recupererUtilisateursOrderedPrenomNom retourne array*/

    public function testRecupererUtilisateursOrderedPrenomNom(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob');
        $fakeUtilisateur2 = new Utilisateur('bib420','bibby','bib','bib.bibby@bob.com','mdpBib');
        $array = [$fakeUtilisateur2,$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recupererUtilisateursOrderedPrenomNom());

    }

    /**Test recherche prend en arguments $recherche */
    /**avec début nom*/
    public function testRechercheNom(){
        $fakeUtilisateur1= new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp','aaa');
        $array =[$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recherche('zeb'));
    }

    /**avec début prenom*/
    public function testRecherchePrenom(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob');
        $array =[$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recherche('bob'));
    }


    /**avec début email*/


    /** Test ajouter */

    /** Test mettreAJour */

    /** Test supprimer */


}
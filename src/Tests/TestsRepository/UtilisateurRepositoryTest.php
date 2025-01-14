<?php

namespace App\Trellotrolle\Tests\TestsRepository;

use App\Trellotrolle\Modele\DataObject\Utilisateur;
use App\Trellotrolle\Modele\Repository\ConnexionBaseDeDonnees;
use App\Trellotrolle\Modele\Repository\ConnexionBaseDeDonneesInterface;
use App\Trellotrolle\Modele\Repository\UtilisateurRepository;
use App\Trellotrolle\Modele\Repository\UtilisateurRepositoryInterface;
use App\Trellotrolle\Tests\ConfigurationBDDTestUnitaires;
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
        parent::tearDown();
        self::$connexionBaseDeDonnees->getPdo()->query("DELETE FROM utilisateur");
    }

    /**Test recupererUtilisateursParEmail, prends : string $email retourne array*/

    public function testRecupererUtilisateursParEmailExistant(){
        $fakeUtilisateur= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob',"aaa");
        $array = [$fakeUtilisateur];
        $this->assertEquals($fakeUtilisateur, self::$utilisateurRepository->recupererUtilisateursParEmail('bob.bobby@bob.com'));
    }

    public function testRecupererUtilisateursParEmailNonExistant(){
        $this->assertEquals(null, self::$utilisateurRepository->recupererUtilisateursParEmail('george.george@george.com'));
    }

    /**Test recupererUtilisateursOrderedPrenomNom retourne array*/

    public function testRecupererUtilisateursOrderedPrenomNom(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob',"aaa");
        $fakeUtilisateur2 = new Utilisateur('bib420','bibby','bib','bib.bibby@bob.com','mdpBib',"aaa");
        $fakeUtilisateur3 = new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp',"aaa");
        $array = [$fakeUtilisateur3,$fakeUtilisateur2,$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recupererUtilisateursOrderedPrenomNom());

    }

    /**Test recherche prend en arguments $recherche */
    /**avec début nom*/
    public function testRechercheNom(){
        $fakeUtilisateur1= new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp',"aaa");
        $array =[$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recherche('zeb'));
    }

    /**avec début prenom*/
    public function testRecherchePrenom(){
        $fakeUtilisateur1= new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp',"aaa");
        $array =[$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recherche('agat'));
    }


    /**avec début email*/

    public function testRechercheEmail(){

        $fakeUtilisateur1= new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp',"aaa");
        $array =[$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recherche('agathe.zeb'));
    }

    /** Test Récuperer */
    public function testRecuperer(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob',"aaa");
        $fakeUtilisateur2 = new Utilisateur('bib420','bibby','bib','bib.bibby@bob.com','mdpBib',"aaa");
        $fakeUtilisateur3 = new Utilisateur('bob560','zeblouse','agathe','agathe.zeblouze@jfiu.com','mdp',"aaa");
        $array = [$fakeUtilisateur2,$fakeUtilisateur3,$fakeUtilisateur1];
        $this->assertEquals($array, self::$utilisateurRepository->recuperer());
    }

    /** Test RécupererParCléPrimaire */

    public function testRecupererParClePrimaireExistante(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bob','bob.bobby@bob.com','mdpBob',"aaa");
        $this->assertEquals($fakeUtilisateur1, self::$utilisateurRepository->recupererParClePrimaire('bob69'));
    }

    public function testRecupererParClePrimaireInexistante(){
        $this->assertNull(self::$utilisateurRepository->recupererParClePrimaire('george'));
    }

    /** Test ajouter */

    public function testAjouterUtilisateur(){
        $fakeUtilisateur1= new Utilisateur('nv69','Nouveau','nouvo','nouvo.nouveau@nouvo.com','nouvo',"aaa");
        self::$utilisateurRepository->ajouter($fakeUtilisateur1);
        $this->assertEquals($fakeUtilisateur1, self::$utilisateurRepository->recupererParClePrimaire('nv69'));
    }

    /** Test mettreAJour */

    public function testMettreAjour(){
        $fakeUtilisateur1= new Utilisateur('bob69','bobby','bobby','bobby.bobby@bob.com','mdpBob',"aaa");
        self::$utilisateurRepository->mettreAJour($fakeUtilisateur1);
        $this->assertEquals('bobby.bobby@bob.com', self::$utilisateurRepository->recupererParClePrimaire('bob69')->getEmail());
        $this->assertEquals('bobby', self::$utilisateurRepository->recupererParClePrimaire('bob69')->getPrenom());
    }

    /** Test supprimer */

    public function testSupprimer(){
        self::$utilisateurRepository->supprimer('bob69');
        $this->assertNull(self::$utilisateurRepository->recupererParClePrimaire('bob69'));
    }

}
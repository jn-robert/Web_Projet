<?php
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 24/11/2018
 * Time: 11:31
 */

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Etat;
use App\Entity\LigneCommande;
use App\Entity\Panier;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use App\Entity\Produit;
use App\Form\ProduitType;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\Validator\Constraints\Date;
use Twig\Environment;                            // template TWIG
use Symfony\Bridge\Doctrine\RegistryInterface;   // ORM Doctrine
use Symfony\Component\HttpFoundation\Request;    // objet REQUEST
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
// dans les annotations @Method

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;  // annotation security

class AdminController extends Controller{
    /**
     * @Route("/admin/gestion/commandes", name="Admin.gestionCommandes")
     */
    public function gestionCommandesAdmin(RegistryInterface $doctrine, Environment $twig){
        $commandes = $doctrine->getRepository(Commande::class)->findAll();

        return new Response($twig->render('backOff/Produit/clients/showAllCommandesClients.html.twig',['commandes'=>$commandes]));
    }


    /**
     * @Route("/admin/details/commande", name="Admin.CommandeClient.details")
     */
    public function detailsAdminCommandeClient(RegistryInterface $doctrine, Environment $twig){
        $id=htmlspecialchars($_POST['commandeId']);
        $lignesCommande= $doctrine->getRepository(LigneCommande::class)->findBy(['commandeId'=>$id]);

        $commande = $doctrine->getRepository(Commande::class)->find($id);
        $prixTotal = $commande->getPrixTotal();

        return new Response($twig->render('backOff/Produit/clients/detailsCommandeBackOffice.html.twig',['lignesCommande'=>$lignesCommande,'prixTotal'=>$prixTotal]));
    }


    /**
     * @Route("/admin/valid/commande", name="Admin.validCommande")
     */
    public function validCommandeAdmin(RegistryInterface $doctrine, Environment $twig, ObjectManager $manager){
        $id=htmlspecialchars($_POST['commandeId']);
        $commande = $doctrine->getRepository(Commande::class)->find($id);
        $commande = $commande->getEtatId()->setNom("Expédier");
        $manager->persist($commande);
        $manager->flush();
        $this->redirectToRoute('Admin.gestionCommandes');
        return new Response();

    }
}
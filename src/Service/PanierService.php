<?php
namespace App\Service;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;

    public $repository;

    public function __construct(SessionInterface $session, ProductRepository $repository)
    {

        $this->session=$session;
        $this->repository=$repository;

    }


    public function add(int $id)
    {
        $panier=$this->session->get('panier', []);

          // $panier=[id=>quantite]
         //  $panier[id]=quantite

        if (empty($panier[$id])){

            $panier[$id]=1;
        }else{
            $panier[$id]++;

        }


        $this->session->set('panier', $panier);

    }

    public function remove(int $id)
    {
        $panier=$this->session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !==1){
            $panier[$id]--;

        }else{

            unset($panier[$id]);
        }



        $this->session->set('panier', $panier);

    }

    public function delete(int $id )
    {
        $panier=$this->session->get('panier', []);

       if (!empty($panier[$id])){

           unset($panier[$id]);
       }


        $this->session->set('panier', $panier);

    }

    public function destroy()
    {
       $this->session->set('panier', []);

    }

    public function getFullPanier()
    {
        $panier=$this->session->get('panier', []);

        $panierDetail=[];

        foreach ($panier as $id=>$quantity){

            $panierDetail[]=[
                'product'=>$this->repository->find($id),
                'quantity'=>$quantity
            ];

        }
        return $panierDetail;



    }

    public function getTotal()
    {
        $panier=$this->getFullPanier();

        $total=0;
        foreach ($panier as $indice=> $detail){

            $total+=$detail['product']->getPrice()*$detail['quantity'];

        }

        return $total;


    }






}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\ProductType;
use App\Entity\Product;
use App\Entity\Category;

class ProductController extends AbstractController
{
    #[Route('/', name: 'product_list')]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $products = $doctrine->getRepository('App:Product')->findAll();
        $categories = $doctrine->getRepository('App:Category')->findAll();
        return $this -> render('product/index.html.twig', ['products' => $products, 
        'categories' => $categories
    ]);   
    }
/*
    public function detailsAction(ManagerRegistry $doctine, $id)
    {
        $products = $doctine->getRepository('App:Product')->find($id);
        return $this->render ('products/details.html.twig', ['products' => $products]);
    }
    /*
/*
    public function deleteAction(ManagerRegistry $doctine, $id)
    {
        $em = $doctrine->getManager();  
        $product = $em->getRepository('App:Product')->find($id);
        $em->remove($product);
        $em->flush();

        $this->addFlash(
            'error',
                'Product deleted'
        );

        return $this->redirectToRoute('product_list');
    }

   
    public function createAction(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {
            $productImage = $form->get('productImage')->getData();
            if ($productImage){
                $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_EXTENSION);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $productImage->guessExtension();

                //Move the file to the directory where brachures are started
                try {
                    $productImage->move(
                        $this->getParameter('productImages_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                            'Cannot upload'
                    );
                }
                $product->setProductImage($newFilename);
            }else{
                $this->addFlash(
                    'error',
                        'Cannot upload'
                );
            }
            $em = $doctrine->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                    'Product Added'
            );
            return $this->redirecToRoute('product_list');
        }
        return $this->renderForm('product/create.html.twig', ['form' => $form,]);
    }
     
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class,array('class'=>'App\Entity\Category','choice_label'=>"catName")
            )
            ->add('productName', TextType::class)
            // ->remove('name')
            ->add('productprice', TextType::class)
            ->add('productdate', DateType::class,['widget' =>'single_text'])
            ->add('productdescription', TextareaType::class)
            ->add('productImage', FileType::class, [
                'label' =>'Image file',
                'mapped' =>false,
                'required' =>false,
                'contraints'=> [
                    new File([
                        'maxSize'=>'1024k',
                        'mineTypeMessage'=>"Please upload a valid image",
            ])
            ],
        ])
        ;
    }
    */
    /**
     * @Route("/product/productByCat/{id}", name="productByCat")
     */
    public  function productByCatAction(ManagerRegistry $doctrine ,$id):Response
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
        $products = $category->getProducts();
        $categories = $doctrine->getRepository('App:Category')->findAll();
        return $this->render('product/index.html.twig', ['products' => $products,
            'categories'=>$categories]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */

    public function deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine->getManager();
        $product = $em->getRepository('App:Product')->find($id);
        $em->remove($product);
        $em->flush();
        $this->addFlash(
            'error',
            'Product deleted'
        );
        return $this->redirectToRoute('product_list');
    }
    /**
     * @Route("/product/details/{id}", name="product_details")
     */
    public  function detailsAction(ManagerRegistry $doctrine ,$id)
    {
        $products = $doctrine->getRepository('App:Product')->find($id);
        return $this->render('product/details.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product/create", name="product_create", methods={"GET","POST"})
     */

    public function createAction(ManagerRegistry$doctrine,Request $request, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // uplpad file
            $productImage = $form->get('productImage')->getData();
            if ($productImage) {
                $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $productImage->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $productImage->move(
                        $this->getParameter('productImages_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                        'Cannot upload'
                    );// ... handle exception if something happens during file upload
                }
                $product->setProductImage($newFilename);
            }else{
                $this->addFlash(
                    'error',
                    'Cannot upload'
                );// ... handle exception if something happens during file upload
            }
            $em = $doctrine->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'Product Added'
            );
            return $this->redirectToRoute('product_list');
        }
        return $this->renderForm('product/create.html.twig', ['form' => $form,]);
    }

    #[Route('/edit/{id}', name: 'editP')]
    public function editAction(ManagerRegistry $doctrine, $id, Request $request)
    {
        //$product = new Product();
        $em = $doctrine->getManager();
        $product = $em->getRepository('App:Product')->find($id);
        $categories = $em -> getRepository('App:Category') -> findAll();
        $form = $this ->createForm( ProductType::class, $product);
        $form ->handleRequest($request);

        if ($form ->isSubmitted()){
            $entityManager = $doctrine ->getManager();
            $entityManager -> persist($product);
            $entityManager -> flush();

           return $this -> redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'categories ' => $categories
        ]);
    }
}

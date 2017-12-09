<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeController extends Controller
{
    public function index()
    {
        $form = $this->createFormBuilder()
            ->add('PlayerId', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();

        return $this->render(
            'home.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

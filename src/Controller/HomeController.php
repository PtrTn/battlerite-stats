<?php

namespace App\Controller;

use PtrTn\Battlerite\Client;
use PtrTn\Battlerite\Query\MatchesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function index()
    {
        $form = $this->createSearchForm();

        return $this->render(
            'home.html.twig',
            [
                'form'        => $form->createView(),
                'playerData'  => null,
                'matchesData' => null
            ]
        );
    }

    public function searchPlayer(Request $request)
    {
        $form = $this->createSearchForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->redirectToRoute('index');
        }

        $searchQuery = $form->getData();
        $client = Client::create(getenv('APIKEY'));
        if (!isset($searchQuery['PlayerId'])) {
            $this->redirectToRoute('index');
        }
        //934791968557563904
        $playerData = $client->getPlayer($searchQuery['PlayerId']);
        $matches = $client->getMatches(
            MatchesQuery::create()
                ->forPlayerIds([$searchQuery['PlayerId']])
                ->withLimit(1)
                ->sortDescBy('createdAt')
        );
        $matchesData = [];
        foreach ($matches as $match) {
            $matchesData[] = $client->getMatch($match->id);
        }
        return $this->render(
            'home.html.twig',
            [
                'form'        => $form->createView(),
                'playerData'  => $playerData,
                'matchesData' => $matchesData
            ]
        );
    }

    private function createSearchForm(): FormInterface
    {
        $form = $this->createFormBuilder()
            ->add('PlayerId', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();
        return $form;
    }
}

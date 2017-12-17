<?php

namespace App\Controller;

use PtrTn\Battlerite\ClientWithCache;
use PtrTn\Battlerite\Query\MatchesQuery;
use PtrTn\Battlerite\Query\PlayersQuery;
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
        $client = ClientWithCache::create(getenv('APIKEY'));
        if (!isset($searchQuery['PlayerName'])) {
            $this->redirectToRoute('index');
        }

        $playersData = $client->getPlayers(
            PlayersQuery::create()
                ->forPlayerNames([$searchQuery['PlayerName']])
        );

        $playerData = $playersData->getPlayerByNameAndShard($searchQuery['PlayerName'], 'global');

        $matches = $client->getMatches(
            MatchesQuery::create()
                ->forPlayerIds([$playerData->id])
                ->withLimit(5)
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
            ->add('PlayerName', TextType::class, ['attr' => ['placeholder ' => 'Player name']])
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();
        return $form;
    }
}

<?php

namespace App\Controller;

use App\Entity\ReservationRestaurant;
use App\Form\ReservationRestaurantType;
use App\Repository\ReservationRestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation/restaurant")
 */
class ReservationRestaurantController extends AbstractController
{
    /**
     * @Route("/", name="reservation_restaurant_index", methods={"GET"})
     */
    public function index(ReservationRestaurantRepository $reservationRestaurantRepository): Response
    {
        return $this->render('reservation_restaurant/index.html.twig', [
            'reservation_restaurants' => $reservationRestaurantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reservation_restaurant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationRestaurant = new ReservationRestaurant();
        $form = $this->createForm(ReservationRestaurantType::class, $reservationRestaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationRestaurant);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_restaurant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_restaurant/new.html.twig', [
            'reservation_restaurant' => $reservationRestaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_restaurant_show", methods={"GET"})
     */
    public function show(ReservationRestaurant $reservationRestaurant): Response
    {
        return $this->render('reservation_restaurant/show.html.twig', [
            'reservation_restaurant' => $reservationRestaurant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_restaurant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReservationRestaurant $reservationRestaurant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationRestaurantType::class, $reservationRestaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_restaurant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_restaurant/edit.html.twig', [
            'reservation_restaurant' => $reservationRestaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_restaurant_delete", methods={"POST"})
     */
    public function delete(Request $request, ReservationRestaurant $reservationRestaurant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationRestaurant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationRestaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_restaurant_index', [], Response::HTTP_SEE_OTHER);
    }
}

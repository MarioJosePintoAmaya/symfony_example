<?php

namespace App\Controller;

use App\Entity\Direccion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DireccionController extends AbstractController
{
    #[Route('/direcciones', name: 'app_direccion_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validaciones para los datos de dirección aquí
        
        $direccion = new Direccion();
        $direccion->setDepartamento($request->request->get('departamento'));
        $direccion->setMunicipio($request->request->get('municipio'));
        $direccion->setDireccion($request->request->get('direccion'));

        $entityManager->persist($direccion);
        $entityManager->flush();

        return $this->json([
            'message' => 'Dirección creada con ID ' . $direccion->getId()
        ]);
    }

    #[Route('/direcciones/{id}', name: 'app_direccion_read_one', methods: ['GET'])]
    public function readOne(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $direccion = $entityManager->getRepository(Direccion::class)->find($id);

        if (!$direccion) {
            return $this->json(['error' => 'Dirección no encontrada.'], 404);
        }

        return $this->json([
            'id' => $direccion->getId(),
            'departamento' => $direccion->getDepartamento(),
            'municipio' => $direccion->getMunicipio(),
            'direccion' => $direccion->getDireccion()
        ]);
    }

    #[Route('/direcciones/{id}', name: 'app_direccion_edit', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validaciones para los datos de dirección aquí

        $direccion = $entityManager->getRepository(Direccion::class)->find($id);

        if (!$direccion) {
            return $this->json(['error' => 'Dirección no encontrada.'], 404);
        }

        $direccion->setDepartamento($request->request->get('departamento'));
        $direccion->setMunicipio($request->request->get('municipio'));
        $direccion->setDireccion($request->request->get('direccion'));

        $entityManager->flush();

        return $this->json(['message' => 'Dirección actualizada con ID ' . $direccion->getId()]);
    }

    #[Route('/direcciones/{id}', name: 'app_direccion_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $direccion = $entityManager->getRepository(Direccion::class)->find($id);

        if (!$direccion) {
            return $this->json(['error' => 'Dirección no encontrada.'], 404);
        }

        $entityManager->remove($direccion);
        $entityManager->flush();

        return $this->json(['message' => 'Dirección eliminada.']);
    }
}
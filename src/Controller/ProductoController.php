<?php

namespace App\Controller;

use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProductoController extends AbstractController
{
    #[Route('/productos', name: 'app_producto_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validaciones para edad, precio y existencia aquí
        
        $producto = new Producto();
        $producto->setNombre($request->request->get('nombre'));
        $producto->setPrecio($request->request->get('precio'));
        $producto->setExistencia($request->request->get('existencia'));

        $entityManager->persist($producto);
        $entityManager->flush();

        return $this->json([
            'message' => 'Producto creado con ID ' . $producto->getId()
        ]);
    }

    #[Route('/productos/{id}', name: 'app_producto_read_one', methods: ['GET'])]
    public function readOne(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            return $this->json(['error' => 'Producto no encontrado.'], 404);
        }

        return $this->json([
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'precio' => $producto->getPrecio(),
            'existencia' => $producto->getExistencia()
        ]);
    }

    #[Route('/productos/{id}', name: 'app_producto_edit', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validaciones para edad, precio y existencia aquí

        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            return $this->json(['error' => 'Producto no encontrado.'], 404);
        }

        $producto->setNombre($request->request->get('nombre'));
        $producto->setPrecio($request->request->get('precio'));
        $producto->setExistencia($request->request->get('existencia'));

        $entityManager->flush();

        return $this->json(['message' => 'Producto actualizado con ID ' . $producto->getId()]);
    }

    #[Route('/productos/{id}', name: 'app_producto_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            return $this->json(['error' => 'Producto no encontrado.'], 404);
        }

        $entityManager->remove($producto);
        $entityManager->flush();

        return $this->json(['message' => 'Producto eliminado.']);
    }
}

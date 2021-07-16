<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\User;
use App\Repository\BrandRepository;
use App\Repository\ModelRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api")
 */
class DataImporterController extends AbstractController
{
    /**
     * @Route("/import", name="data_importer", methods={"POST"})
     */
    public function import(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        BrandRepository $brandRepository,
        ModelRepository $modelRepository): JsonResponse
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $entityManager = $this->getDoctrine()->getManager();

        foreach($data['models'] as $dataModel) {
            $model = new Model();
            $model->setName($dataModel['name']);
            $model->setSteeringPosition($dataModel['steeringPosition']);
            $entityManager->persist($model);
        }

        foreach($data['brands'] as $dataBrand) {
            $brand = new Brand();
            if($brandRepository->findOneBy(['name' => $dataBrand['name']]) == [])
            {
                $brand->setName($dataBrand['name']);
                $entityManager->persist($brand);
            }
        }

        $entityManager->flush();

        foreach($data['cars'] as $dataCar) {
            $car = new Car();
            $car->setBrand($brandRepository->find($dataCar['brand']));
            $car->setModel($modelRepository->find($dataCar['model']));
            $entityManager->persist($car);
        }

        foreach($data['users'] as $dataUser) {
            $user = new User();
            if($userRepository->findOneBy(['email' => $dataUser['email']]) == [])
            {
                $user->setEmail($dataUser['email']);
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $dataUser['password']
                    )
                );
                $user->setRoles($dataUser['roles']);
                $entityManager->persist($user);
            }

        }

        $entityManager->flush();

        return new JsonResponse([
            'status' => 'ok'
        ], JsonResponse::HTTP_OK);
    }
}

<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use App\Repository\Exception\DepartmentNotFound;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

final class DepartmentController extends AbstractController
{
    public function __invoke(
        Request $request,
        DepartmentRepository $departmentRepository,
        CityRepository $cityRepository,
        SluggerInterface $slugger,
        RouterInterface $router,
        TranslatorInterface $translator
    ) : Response {
        try {
            $department = $departmentRepository->findOneByCode($request->get('code'));
        } catch (DepartmentNotFound $e) {
            throw new NotFoundHttpException();
        }

        $queryString = '';
        if (!empty($request->getQueryString())) {
            $queryString = '?' . $request->getQueryString();
        }

        $departmentUrl = $router->generate(
            'department',
            [
                'code' => $department->getCode(),
                'name' => strtolower($slugger->slug($department->getName()))
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $trueUrl = $departmentUrl . $queryString;
        if ($trueUrl !== $request->getUri()) {
            return $this->redirect($trueUrl, Response::HTTP_MOVED_PERMANENTLY);
        }

        $response = new Response();
        $response
            ->setLastModified($cityRepository->getLastModified())
            ->setPublic()
            ->setMaxAge(0)
        ;
        $response->headers->addCacheControlDirective('no-cache');

        if ($response->isNotModified($request)) {
            return $response;
        }

        $cities = $cityRepository->fetchByDepartmentId($department->getId());

        $viewParameters = [
            'department' => $department,
            'cities' => $cities,
            'description' => $translator->trans(
                'department.description %deparmentLabel%',
                ['%deparmentLabel%' => $department->getName()]
            ),
            'url' => $departmentUrl
        ];

        return $this->render('department.html.twig', $viewParameters, $response);
    }
}

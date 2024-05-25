<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Controller;

use MJankoo\TimeTracker\Tracking\Application\UseCase\CreateEmployee;
use MJankoo\TimeTracker\Tracking\UserInterface\Request\CreateEmployeeRequest;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly CreateEmployee $createEmployee,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/employee/create', name: 'time_tracker.tracking.employee.create', methods: ['POST'])]
    public function create(CreateEmployeeRequest $request): Response
    {
        try {
            $employeeId = ($this->createEmployee)($request->getName(), $request->getSurname());
        } catch (\Throwable $e) {
            $this->logger->error('Failed to create employee because: ' . $e);
            return new Response('Failed to create employee.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response($employeeId);
    }
}

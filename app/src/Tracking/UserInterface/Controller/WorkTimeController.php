<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Controller;

use DateTimeImmutable;
use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;
use MJankoo\TimeTracker\Tracking\Application\UseCase\AddWorkTime;
use MJankoo\TimeTracker\Tracking\Application\UseCase\GetWorkTimeSummary;
use MJankoo\TimeTracker\Tracking\UserInterface\Request\AddWorkTimeRequest;
use MJankoo\TimeTracker\Tracking\UserInterface\Request\GetWorkTimeSummaryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WorkTimeController extends AbstractController
{
    public function __construct(
        private readonly AddWorkTime $addWorkTime,
        private readonly GetWorkTimeSummary $getWorkTimeSummary
    ) {
    }

    #[Route('/work-time/add', name: 'time_tracker.tracking.work_time.add', methods: ['POST'])]
    public function add(AddWorkTimeRequest $request): Response
    {
        try {
            ($this->addWorkTime)(
                $request->getEmployeeId(),
                new DateTimeImmutable($request->getStartDateTime()),
                new DateTimeImmutable($request->getEndDateTime())
            );
        } catch (AbstractDomainException $e) {
            return new Response($e->getMessage(), 400);
        }

        return new Response('Time entry successfully added.');
    }

    #[Route('/work-time/summary', name: 'time_tracker.tracking.work_time.summary', methods: ['POST'])]
    public function summary(GetWorkTimeSummaryRequest $request): Response
    {
        try {
            $summary = ($this->getWorkTimeSummary)(
                $request->getEmployeeId(),
                $request->getDate()
            );
        } catch (AbstractDomainException $e) {
            return new Response($e->getMessage(), 400);
        }

        return new JsonResponse($summary->toArray());
    }
}

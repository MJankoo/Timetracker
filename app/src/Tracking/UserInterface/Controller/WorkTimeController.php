<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Controller;

use DateTimeImmutable;
use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;
use MJankoo\TimeTracker\Tracking\Application\UseCase\AddWorkTime;
use MJankoo\TimeTracker\Tracking\UserInterface\Request\AddWorkTimeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WorkTimeController extends AbstractController
{
    public function __construct(
        private readonly AddWorkTime $addWorkTime
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
}

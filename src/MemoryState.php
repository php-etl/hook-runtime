<?php

declare(strict_types=1);

namespace Kiboko\Component\Runtime\Hook;

use Kiboko\Contract\Pipeline\StateInterface;
use Kiboko\Contract\Pipeline\StepCodeInterface;

final class MemoryState implements StateInterface
{
    /** @var array{accept: int, reject: int, error: int} */
    private array $metrics = [];

    public function __construct(
        private StateInterface $decorated,
    ) {
    }

    public function initialize(int $start = 0): void
    {
        $this->metrics = [
            'accept' => 0,
            'reject' => 0,
            'error' => 0,
        ];

        $this->decorated->initialize($start);
    }

    public function accept(StepCodeInterface $step, int $count = 1): void
    {
        $this->metrics['accept'] += $count;
        $this->decorated->accept($step, $count);
    }

    public function reject(StepCodeInterface $step, int $count = 1): void
    {
        $this->metrics['reject'] += $count;
        $this->decorated->reject($step, $count);
    }

    public function error(StepCodeInterface $step, int $count = 1): void
    {
        $this->metrics['error'] += $count;
        $this->decorated->error($step, $count);
    }

    public function observeAccept(): callable
    {
        return fn () => $this->metrics['accept'];
    }

    public function observeReject(): callable
    {
        return fn () => $this->metrics['reject'];
    }

    public function teardown(): void
    {
        $this->decorated->teardown();
    }

    /**
     * @return array{accept: int, reject: int, error: int}
     */
    public function getMetrics(): array
    {
        return $this->metrics;
    }
}

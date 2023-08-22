<?php

declare(strict_types=1);

namespace Kiboko\Component\Runtime\Hook;

use Kiboko\Contract\Pipeline\LoaderInterface;
use Kiboko\Contract\Pipeline\LoadingInterface;
use Kiboko\Contract\Pipeline\NullState;
use Kiboko\Contract\Pipeline\PipelineInterface;
use Kiboko\Contract\Pipeline\RejectionInterface;
use Kiboko\Contract\Pipeline\StateInterface;
use Kiboko\Contract\Pipeline\TransformerInterface;
use Kiboko\Contract\Pipeline\TransformingInterface;
use Kiboko\Contract\Pipeline\WalkableInterface;
use Psr\Container\ContainerInterface;

class HookRuntime implements HookRuntimeInterface
{
    private MemoryState $state;

    public function __construct(
        private PipelineInterface&WalkableInterface $pipeline,
        private ContainerInterface $container,
        ?StateInterface $state = null
    ) {
        $this->state = $state ?? new MemoryState(new NullState());
    }

    public function transform(
        TransformerInterface $transformer,
        RejectionInterface $rejection,
        StateInterface $state,
    ): TransformingInterface {
        $this->pipeline->transform($transformer, $rejection, $this->state);

        return $this;
    }

    public function load(
        LoaderInterface $loader,
        RejectionInterface $rejection,
        StateInterface $state,
    ): LoadingInterface {
        $this->pipeline->load($loader, $rejection, $this->state);

        return $this;
    }

    public function run(): array
    {
        $line = 0;
        foreach ($this->pipeline->walk() as $item) {
            $line++;
        }

        return $this->state->getMetrics();
    }

    public function feed(...$data): HookRuntimeInterface
    {
        $this->pipeline->feed(...$data);

        return $this;
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }
}

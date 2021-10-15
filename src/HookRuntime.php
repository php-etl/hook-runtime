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
use Psr\Container\ContainerInterface;

class HookRuntime implements HookRuntimeInterface
{
    private StateInterface $state;

    public function __construct(
        private PipelineInterface $pipeline,
        private ContainerInterface $container,
        ?StateInterface $state = null
    ) {
        $this->state = $state ?? new NullState();
    }

    public function transform(
        TransformerInterface $transformer,
        RejectionInterface $rejection,
        StateInterface $state,
    ): TransformingInterface {
        $this->pipeline->transform($transformer, $rejection, $this->state);

//        $this->state->withStep('transformer')
//            ->addMetric('read', $state->observeAccept())
//            ->addMetric('error', fn() => 0)
//            ->addMetric('rejected', $state->observeReject());

        return $this;
    }

    public function load(
        LoaderInterface $loader,
        RejectionInterface $rejection,
        StateInterface $state,
    ): LoadingInterface {
        $this->pipeline->load($loader, $rejection, $this->state);

//        $this->state->withStep('loader')
//            ->addMetric('read', $state->observeAccept())
//            ->addMetric('error', fn() => 0)
//            ->addMetric('rejected', $state->observeReject());

        return $this;
    }

    public function run(): int
    {
        $line = 0;
        foreach ($this->pipeline->walk() as $item) {
            $line++;
        }
//        $this->state->update();

        return $line;
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

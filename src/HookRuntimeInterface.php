<?php

namespace Kiboko\Component\Runtime\Hook;

use Kiboko\Contract\Pipeline\LoadingInterface;
use Kiboko\Contract\Pipeline\RunnableInterface;
use Kiboko\Contract\Pipeline\TransformingInterface;
use Psr\Container\ContainerInterface;

interface HookRuntimeInterface extends TransformingInterface, LoadingInterface, RunnableInterface
{
    public function feed(...$data): self;
    public function container(): ContainerInterface;
    public function metrics(): array;
}

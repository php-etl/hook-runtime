<?php

namespace Kiboko\Component\Runtime\Hook;

use Kiboko\Contract\Pipeline\LoadingInterface;
use Kiboko\Contract\Pipeline\TransformingInterface;
use Psr\Container\ContainerInterface;

interface HookRuntimeInterface extends TransformingInterface, LoadingInterface
{
    public function feed(...$data): self;
    /** @deprecated We should not provide access to the container, may there be one existing */
    public function container(): ContainerInterface;
    public function run(): array;
}

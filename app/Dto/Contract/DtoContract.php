<?php

namespace App\Dto\Contract;

use Illuminate\Http\Request;

interface DtoContract
{
    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self;

    /**
     * @return array
     */
    public function toArray(): array;
}

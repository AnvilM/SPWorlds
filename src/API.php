<?php

namespace AnvilM\SPWorlds;

use AnvilM\SPWorlds\Contracts\Services\APIServiceContract;
use Illuminate\Support\Facades\App;

class API
{
    public APIServiceContract $APIService;

    /**
     * @param string $id ID Карты
     * @param string $token Токен карты
     */
    public function __construct(string $id, string $token)
    {
        $authorization = base64_encode("$id:$token");
        $this->APIService = App::make(APIServiceContract::class, ['authorization' => $authorization]);
    }
}

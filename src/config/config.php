<?php

return [

    'default_wallet' => env('BLOCKCHAIN_DEFAULT_WALLET',''),
    'api_secret' => env('BLOCKCHAIN_SECRET_API_KEY'),
    'xpub' => env('BLOCKCHAIN_XPUB_KEY'),
    'cors' => env('BLOCKCHAIN_USE_CORS',true),
    
];
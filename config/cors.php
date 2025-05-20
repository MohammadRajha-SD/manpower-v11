<?php

// return [

// 'paths' => ['api/*', 'sanctum/csrf-cookie', 'user/login', 'user/logout', 'provider/login', 'provider/logout'],
// 'allowed_methods' => ['*'],
// 'allowed_origins' => ['http://localhost:3000', 'https://staging.manpowerforu.com/'],
// 'allowed_headers' => ['*'],
// 'allow_credentials' => true,

// ];

return [
  'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => ['http://localhost:3000','https://manpowerforu.vercel.app', 'https://new.hpower.ae', 'https://hpower.ae'],

// 'allowed_headers' => [
//     'Content-Type',
//     'X-Requested-With',
//     'Authorization', 
//     'Accept',
//     'Origin',
// ],

'allowed_headers' => ['*'],
'allow_credentials' => true,

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,
  
];
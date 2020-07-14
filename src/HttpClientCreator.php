<?php
namespace ApiClientExample;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use Guzzlehttp\psr7\Uri;

final class HttpClientCreator
{
  public static function create(
    string $baseUri, 
    string $secretKey, 
    array $customHttpOptions = [] 
  ) : Client
  {
    $defaultHttpOptions = [
      'base_uri' => rtrim($baseUri, '/') . '/',
      'headers' => [
        'Content-type' => "application/json",
        'Accept' => "application/json"
      ],
      'timeout' => 60,
      'exceptions' => false,
    ];

    $handlerStack = $customHttpOptions['handler'] ?? HandlerStack::create(new CurlHandler());  

    $handlerStack->push(
      Middleware::mapRequest(
        function(RequestInterface $request) use ($secretKey){
          return $request->withUri(
            Uri::withQueryValues(
              $request->getUri(), 
              ['key' => $secretKey]
            )
          );
        }
      )
    );
     
    $httpOptions = array_replace_recursive(
      $defaultHttpOptions,
      $customHttpOptions,
      ['handler' => $handlerStack] 
    );
                              
    return new Client($httpOptions);
  }


}
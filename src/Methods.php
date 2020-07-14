<?php
namespace ApiClientExample;

use ApiClientExample\Exceptions\MethodException;
use ApiClientExample\Exceptions\ServerException;
use GuzzleHttp\ClientInterface;

final class Methods{
  private $http;
  
  public function __construct(\GuzzleHttp\ClientInterface $http){
    $this->http = $http;
  }

  public function balance(string $currency) : object
  {
    $response = $this->getDataFromServer(
      'balance',
      [
        'query' => [
          'currency' => $currency,
        ]
      ]
    );
    
    if (!isset($response->amount)){
      throw new MethodException("Can't get balance amount (`amount` property does not exists in response). Full response: " . json_encode($response));
    }

    return $response;
  }

  
  private function getDataFromServer(string $endpoint, array $options = []) : object
  {
    try {
      $response = $this->http->request('GET', $endpoint, $options);
    } catch (\Exception $e){
      throw new ServerException("Http client error: " . $e->getMessage());
    }

    $body = $response->getBody();
    if (!$body){
      throw new ServerException("Can't get body on {$endpoint}");
    }

    $data = json_decode($body);
    if (!$data){
      throw new ServerException("Can't decode body on {$endpoint}\r\nBody: {$body}");
    }

    return $data;
  }
}

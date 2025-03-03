<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Logger\RfcLogLevel;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Controller for anytown.weather_page route.
 */
class MoreEventsPage extends ControllerBase {

    use AutowireTrait;

    /**
     * HTTP client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;
  
    /**
     * Logging service, set to 'anytown' channel.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
  
    /**
     * WeatherPage controller constructor.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   HTTP client.
     */
    public function __construct(ClientInterface $http_client) {
      $this->httpClient = $http_client;
      $this->logger = $this->getLogger('anytown');
    }

  /**
   * Builds the response.
   */
  public function build(string $style): array {
    // Style should be one of 'short', or 'extended'. And default to 'short'.
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    $url = 'https://module-developer-guide-demo-site.ddev.site/modules/custom/anytown/data/weather_forecast.json';
    $data = NULL;

    try {
      $response = $this->httpClient->get($url);
      $data = json_decode($response->getBody()->getContents());
    }
    catch (RequestException $e) {
      $this->logger->log(RfcLogLevel::WARNING, $e->getMessage());
    }

    $build['content'] = [
      '#type' => 'markup',
      '#markup' => '<p>Here are some more events for you.</p>',
    ];

    if ($style === 'extended') {
        $build['content_extended'] = [
          '#type' => 'markup',
          '#markup' => '<p><strong>Extended forecast:</strong> Looking ahead to next week we expect some snow.</p>',
        ];
      }

    return $build;
  }

}
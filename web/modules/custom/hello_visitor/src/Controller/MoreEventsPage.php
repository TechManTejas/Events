<?php

declare(strict_types=1);

namespace Drupal\hello_visitor\Controller;

use Drupal\hello_visitor\ForecastClientInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for hello_visitor.weather_page route.
 */
class MoreEventsPage extends ControllerBase {

  /**
   * Forecast API client.
   *
   * @var \Drupal\hello_visitor\ForecastClientInterface
   */
  private $forecastClient;

  /**
   * WeatherPage controller constructor.
   *
   * @param \Drupal\hello_visitor\ForecastClientInterface $forecast_client
   *   Forecast API client service.
   */
  public function __construct(ForecastClientInterface $forecast_client) {
    $this->forecastClient = $forecast_client;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('hello_visitor.forecast_client')
    );
  }

  /**
   * Builds the response.
   */
  public function build(string $style): array {
    // Style should be one of 'short', or 'extended'. And default to 'short'.
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    // Get the configuration object from the configuration factory service.
    $settings = $this->config('hello_visitor.settings');

    $url = 'https://raw.githubusercontent.com/DrupalizeMe/module-developer-guide-demo-site/main/backups/weather_forecast.json';
    if ($location = $settings->get('location')) {
      $url .= '?location=' . $location;
    }

    $forecast_data = $this->forecastClient->getForecastData($url);

    $table_rows = [];
    $highest = 0;
    $lowest = 0;
    if ($forecast_data) {
      // Create a table of the weather forecast as a render array. First loop
      // over the forecast data and create rows for the table.
      foreach ($forecast_data as $item) {
        [
          'weekday' => $weekday,
          'description' => $description,
          'high' => $high,
          'low' => $low,
          'icon' => $icon,
        ] = $item;

        // Create one table row for each day in the forecast.
        $table_rows[] = [
          // Simple text for a cell can be added directly to the array.
          $weekday,
          // Complex data for a cell, like HTML, can be represented as a nested
          // render array.
          [
            'data' => [
              '#markup' => '<img alt="' . $description . '" src="' . $icon . '" width="200" height="200" />',
            ],
          ],
          [
            'data' => [
              '#markup' => $this->t('%description with a high of @high and a low of @low',
                [
                  '%description' => $description,
                  '@high' => $high,
                  '@low' => $low,
                ]
              ),
            ],
          ],
        ];

        $highest = max($highest, $high);
        $lowest = min($lowest, $low);
      }

      // Extended forecast as a table.
      $weather_forecast = [
        '#type' => 'table',
        '#header' => [
          'Day',
          '',
          'Forecast',
        ],
        '#rows' => $table_rows,
        '#attributes' => [
          'class' => ['weather_page--forecast-table'],
        ],
      ];

      // Summary forecast.
      $short_forecast = [
        '#type' => 'markup',
        '#markup' => '<p>' . $this->t('The high for the weekend is @highest and the low is @lowest.',
          [
            '@highest' => $highest,
            '@lowest' => $lowest,
          ]
        ) . '</p>',
      ];

    }
    else {
      // Or, display a message if we can't get the current forecast.
      $weather_forecast = ['#markup' => '<p>' . $this->t('Could not get the weather forecast. Dress for anything.') . '</p>'];
      $short_forecast = NULL;
    }

    $build = [
      // Which theme hook to use for this content. See hello_visitor_theme().
      '#theme' => 'weather_page',
      // Attach the CSS and JavaScript for the page.
      '#attached' => [
        'library' => ['hello_visitor/forecast'],
      ],
      // When passing a render array to Twig template file any top level array
      // element that starts with a '#' will be a variable in the template file.
      // Example: {{ weather_intro }}.
      '#weather_intro' => [
        '#markup' => "<p>" . $this->t("Check out this weekend's weather forecast and come prepared. The market is mostly outside, and takes place rain or shine") . "</p>",
      ],
      '#weather_forecast' => $weather_forecast,
      '#short_forecast' => $short_forecast,
      '#weather_closures' => [
        '#theme' => 'item_list',
        '#title' => $this->t('What all events do we organize?'),
        '#items' => explode(PHP_EOL, $settings->get('event_categories')),
      ],
      '#cache' => [
        // This content will vary if the settings for the module change, so we
        // specify that here using cache tags.
        //
        // This will end up looking like 'config:anytown.settings' but when
        // available it's better to use the getCacheTags() method to retrieve
        // tags rather than hard-code them.
        'tags' => $settings->getCacheTags(),
        // Remember, this page can be accessed via multiple URLs, like /weather
        // and /weather/extended. And varies depending on the URL, so we also
        // need to add a cache context for the URL so that the content is cached
        // per-url.
        'contexts' => ['url'],
      ],
    ];

    return $build;
  }

}

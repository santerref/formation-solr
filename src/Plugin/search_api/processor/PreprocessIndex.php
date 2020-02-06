<?php

namespace Drupal\formation_solr\Plugin\search_api\processor;

use Drupal\search_api\Processor\FieldsProcessorPluginBase;

/**
 * @SearchApiProcessor(
 *   id = "formation_solr_preprocess_index",
 *   label = @Translation("Remplacer avec une blague"),
 *   description = @Translation("Remplace le contenu du champ par une blague aléatoire."),
 *   stages = {
 *     "pre_index_save" = 0,
 *     "preprocess_index" = 20
 *   }
 * )
 */
class PreprocessIndex extends FieldsProcessorPluginBase {

  public function processFieldValue(&$value, $type) {
    $value = $this->getJoke();
  }

  protected function getJoke() {
    $joke = "Sorry, the Joke's API isn't working.";
    try {
      $response = \Drupal::httpClient()->get('https://sv443.net/jokeapi/v2/joke/Programming?blacklistFlags=nsfw,religious,political,racist,sexist&type=single');
      $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), TRUE);
      if (isset($data['joke'])) {
        $joke = $data['joke'];
      }
    } catch (\Exception $e) {
    }
    return $joke;
  }

}

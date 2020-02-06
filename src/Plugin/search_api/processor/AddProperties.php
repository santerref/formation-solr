<?php

namespace Drupal\formation_solr\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * @SearchApiProcessor(
 *   id = "formation_solr_add_properties",
 *   label = @Translation("Faits divers de chats"),
 *   description = @Translation("Ajout d'un fait aléatoire sur les chats."),
 *   stages = {
 *     "add_properties" = 50,
 *   },
 *   locked = true
 * )
 */
class AddProperties extends ProcessorPluginBase {

  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    /**
     * Nous ajoutons une propriété globale qui ne dépend pas d'un type de contenu.
     */
    if (!$datasource) {
      $definition = [
        'label' => $this->t('Chat'),
        'description' => $this->t('Fait aléatoire sur les chats.'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['formation_solr_cat'] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  public function addFieldValues(ItemInterface $item) {
    /**
     * La valeur FALSE nous assure d'extraire seulement les champs définis par notre processeur.
     */
    $fields = $item->getFields(FALSE);

    /**
     * Nous nous assurons de récupérer seulement les champs "formation_solr_cat".
     * $fields est au pluriel, car nous pouvons ajouter plusieurs fois le même champ dans l'indexe Solr.
     */
    $fields = $this->getFieldsHelper()->filterForPropertyPath($fields, NULL, 'formation_solr_cat');
    foreach ($fields as $field) {
      $field->addValue($this->getCatQuote());
    }
  }

  /**
   * Cette fonction appelle un API qui génère des faits de chats.
   *
   * @return mixed|string
   */
  protected function getCatQuote() {
    $quote = "Sorry, the Meow Facts' API isn't working.";
    try {
      $response = \Drupal::httpClient()->get('https://meowfacts.herokuapp.com/');
      $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), TRUE);
      if (isset($data['data'])) {
        $quote = reset($data['data']);
      }
    } catch (\Exception $e) {
    }
    return $quote;
  }

}

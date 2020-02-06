<?php

namespace Drupal\formation_solr\Plugin\search_api\processor;

use Drupal\node\NodeInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;

/**
 * @SearchApiProcessor(
 *   id = "formation_solr_alter_items",
 *   label = @Translation("Supprimer Ricardo"),
 *   description = @Translation("Enlève les contenus dont le titre a le terme « Ricardo »."),
 *   stages = {
 *     "alter_items" = 10,
 *   }
 * )
 */
class AlterItems extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function alterIndexedItems(array &$items) {
    foreach ($items as $item_id => $item) {
      $object = $item->getOriginalObject()->getValue();
      /**
       * Comme tous les contenus dans l'indexe ne sont pas des nodes,
       * nous nous assurons que l'objet est une instance de NodeInterface
       * et ensuite nous regardons dans le titre si le mot "Ricardo" est présent.
       */
      if ($object instanceof NodeInterface) {
        if (preg_match('/Ricardo/i', $object->label())) {
          /**
           * Ici nous retirons l'éléments de l'indexe Solr
           */
          unset($items[$item_id]);
        }
      }
    }
  }

}

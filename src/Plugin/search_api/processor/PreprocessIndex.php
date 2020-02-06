<?php

namespace Drupal\formation_solr\Plugin\search_api\processor;

use Drupal\search_api\Processor\FieldsProcessorPluginBase;

/**
 * @SearchApiProcessor(
 *   id = "formation_solr_preprocess_index",
 *   label = @Translation("Remplacer les e par des z"),
 *   description = @Translation("Empêche la recherche de tous les mots avec la lettre E."),
 *   stages = {
 *     "pre_index_save" = 0,
 *     "preprocess_index" = 20
 *   }
 * )
 */
class PreprocessIndex extends FieldsProcessorPluginBase {

  public function processFieldValue(&$value, $type) {
    /**
     * Les champs qui seront remplacés sont définis dans la configuration du processeur (UI/backend).
     */
    $value = preg_replace('/e/ium', 'z', $value);
  }

}

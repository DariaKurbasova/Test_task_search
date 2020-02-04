<?
// Парсер взяла с сайта http://lifeexample.ru/razrabotka-i-optimizacia-saita/format-yaml-i-ego-parser-na-php.html
require_once('sfYamlParser.php');
class YamlToArray extends sfYamlParser{

/**
 * @param $yaml string путь к файлу
 * @return mixed массив настроек
 */
  public function fileParseToArray($yaml){
   return parent::parse($yaml);
  }
}
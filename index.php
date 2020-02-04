<?php
// Пример использования библиотеки
require_once ('SearchStringInFile.php');
require_once ('searchModuleExample.php');

// Поиск по локальному файлу (все вхождения)
$searchString = 'test';
$filePath = 'test.txt';
$stringSearcher = new SearchStringInFile($searchString, $filePath);
$stringPosition = $stringSearcher->search();
print_r($stringPosition);
echo '<br>';

// Поиск по локальному файлу (первое вхождение)
$searchString = 'test';
$filePath = 'test.txt';
$stringSearcher = new SearchStringInFile($searchString, $filePath);
$stringPosition = $stringSearcher->search(false, null, true);
print_r($stringPosition);
echo '<br>';

// Поиск по удаленному файлу
$searchString = 'Bear Pants 5';
$filePath = 'https://my-files.ru/Save/mz02sp/Новый%20текстовый%20документ.txt';
$stringSearcher = new SearchStringInFile($searchString, $filePath);
$stringPosition = $stringSearcher->search(true);
print_r($stringPosition);

// Поиск по локальному файлу (пример подключения модуля к поиску)
$searchString = 'test';
$filePath = 'test.txt';
$stringSearcher = new SearchHashSumInFile($searchString, $filePath);
$isHashSimilar = $stringSearcher->search(false, 'e958149923ef26b27ee487bb6394d4f1');
var_dump($isHashSimilar);
echo '<br>';

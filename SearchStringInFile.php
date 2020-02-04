<?php
//подключение Yaml parser
require_once('parserYaml/parserYaml.php');

class SearchStringInFile {
    public $searchString = '';
    public $filePath = '';

    public function __construct($string, $file) {
        $this->searchString = $string;
        $this->filePath = $file;
    }

    /**
     * Главный метод библиотеки, запускающий поиск по файлу. Ищет все вхождения строки.
     * Переопределение метода при наследовании позволяет изменить алгоритм поиска.
     *
     * По умолчанию возращает массив вида [0 => ['stringNumber' => '', 'stringPosition' => '']],
     * где stringNumber - номер искомой строки, а stringPosition - позиция в этой строке.
     *
     * @param bool $isLink находится ли файл на удаленном сервере
     * @param mixed $additionalData данные, которые можно передать для других механизмов поиска
     * @param bool $onlyFirst искать только первое вхождение
     * @return array|bool
     */
    public function search($isLink = false, $additionalData = null, $onlyFirst = false) {

        $isFileOK = $this->checkRequirements($isLink);
        if ($isFileOK === false) {
            return false;
        }

        $searchResult = array();
        $fileContent = file($this->filePath);

        foreach ($fileContent as $stringNumber => $string) {
            $stringPosition = strpos($string, $this->searchString);
            if ($stringPosition !== false) {

                if ($onlyFirst === false) {
                    $searchResult[] = [
                        'stringNumber' => $stringNumber,
                        'stringPosition' => $stringPosition
                    ];
                } else {
                    $searchResult = [
                        'stringNumber' => $stringNumber,
                        'stringPosition' => $stringPosition
                    ];
                    break;
                }

            }
        }

        return $searchResult;
    }

    /**
     * Проверяет, соответствует ли файл всем требованиям
     * @param $isLink bool находится ли файл на удаленном сервере
     * @return bool
     */
    public function checkRequirements($isLink) {
        $config = self::getOptions();
        $maxSize = $config['main_settings']['max_size_byte'] ?? null;
        $mimeTypes = explode(', ', $config['main_settings']['mime_type']) ?? null;

        // Если файл находится на удаленном сервере
        if ($isLink === true) {
            // Проверяем, существует ли запрашиваемая страница
            $headers = get_headers($this->filePath, 1);
            // Если происходит редирект
            if (!empty($headers[1])) {
                $code = strpos($headers[1], '200');
            } else {
                $code = strpos($headers[0], '200');
            }

            $fileMimeType = explode('; ', $headers['Content-Type']);
            $fileMimeType = array_shift($fileMimeType);

            if ($code === false) {
                return false;
            }

        } elseif (!is_file($this->filePath)) {
            return false;
        } else {
            $fileMimeType = mime_content_type($this->filePath);
        }

        if (!is_null($mimeTypes) && !in_array($fileMimeType, $mimeTypes)) {
            return false;
        }

        $fileSize = self::getFileSize($this->filePath, $isLink);
        if (!is_null($maxSize) && $fileSize > $maxSize) {
            return false;
        }

        return true;
    }

    /**
     * Возвращает настройки из файла searchStringConfig.yaml
     * @return array
     */
    public static function getOptions() {
        $yaml = file_get_contents('searchStringConfig.yaml');
        $parser = new YamlToArray();
        //конвертирование данных в массив
        $data = $parser->fileParseToArray($yaml);
        return $data;
    }

    /**
     * Возвращает размер файла
     * @param string $file путь к файлу
     * @param bool $isLink находится ли файл на удаленном сервере
     * @return int
     */
    public static function getFileSize($file, $isLink) {
        if ($isLink === false) {
            $fileSize = filesize($file);
        } else {
            // Данный кусок кода взят отсюда: https://yandex.ru/turbo?text=https%3A%2F%2Fwww.internet-technologies.ru%2Farticles%2Fopredelenie-razmera-udalennogo-fayla.html
            $fp = fopen($file,"r");
            $inf = stream_get_meta_data($fp);
            fclose($fp);
            foreach($inf["wrapper_data"] as $v) {
                if (stristr($v,"content-length")) {
                    $v = explode(":",$v);
                    $fileSize = (int)trim($v[1]);
                }
            }
        }
        return $fileSize;
    }
}
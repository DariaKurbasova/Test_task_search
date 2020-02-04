<?php
require_once ('SearchStringInFile.php');

class SearchHashSumInFile extends SearchStringInFile {

    /**
     * Переопределяет алгоритм поиска, сравнивая хэш-суммы вместо поиска вхождения строки.
     *
     * @param bool $isLink находится ли файл на удаленном сервере
     * @param mixed $hashSum хэш-сумма файла (в данном случае md5)
     * @param bool $onlyFirst искать только первое вхождение
     * @return array|bool
     */
    public function search($isLink = false, $hashSum = null, $onlyFirst = false) {

        $isFileOK = $this->checkRequirements($isLink);
        if ($isFileOK === false) {
            return false;
        }

        $fileHashSum = hash_file('md5', $this->filePath);
        if ($fileHashSum === $hashSum) {
            return true;
        } else {
            return false;
        }
    }
}
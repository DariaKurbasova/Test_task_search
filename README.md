**Описание файлов**
* Главный файл библиотеки - `SearchModuleExample.php`. Запуск поиска осуществляется путем вызова метода `search`.
* Пример подключения модуля - файл `searchModuleExample.php`. В нем класс `SearchHashSumInFile` наследуется от главного класса `SearchStringInFile`. Конкретно в данном примере алгоритм поиска заменяется на сравнение хэш-сумм.
* Файл для тестирования поиска - `text.txt`. В нем можно искать подстроку `'test'`, сравнивая режим поиска всех вхождений и режим поиска первого вхождения строки.
* Файл с примерами использования библиотеки - `index.php`.
* Настройки библиотеки хранятся в файле `searchStringConfig.yaml`. Парсер для данного файла лежит в папке `parserYaml`.
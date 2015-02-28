Instalacja:
========================

1. Pakiety Symfony
--------------
composer install 

2. Pakiety Angulara + biblioteki na froncie
--------------
cd web/front

bower install

npm install

Najważniejsze pliki backend:
========================

ElectricityMeterReadManager.php
--------------
Zadanie 1 i 2 - główna logika do przetważania danych.


IndexController.php
--------------
API dla zadania 3

DateRangeType.php
--------------
Walidacja danych przychodzących z frontu

ElectricityMeterReadRepository.php
--------------
Zapytania do bazy danych

Najważniejsze pliki frontend:
========================


app.js
--------------
Globalne toolsy do pobierania indexow i kluczy z tablicy z API


main.js 
--------------
Wczytywanie i aktualizacja danych


dateservice.js 
--------------
Komunikacja z API


main.html
--------------
Wyświetlenie wykresu char.js


@author Marcin Czyż
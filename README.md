
Comparativa de parseado de un fichero XML en PHP con Symnpony 2.8.3

Parsers comparados:
-SimpleXML
-DOM
-XMLReader
-XMLParser

Hito: Conocer que libreria es la mas rapida en ficheros de XML pesados. Crear un script por cada parser un saber cual es el más rápido

Datos extraidos:
Alojamiento: //RoomStay/BasicPropertyInfo/@HotelCode
Nombre de la/las habitación por tarifa: //RoomStay/RoomRates/RoomRate/Rates/Rate/RateDescription/Text
Precio total de cada tarifa: //RoomStays/RoomStay/RoomRates/RoomRate/Total/@AmountAfterTax
Régimen de la tarifa: //RoomStays/RoomStay/RoomRates/RoomRate/TPA_Extensions/Mealplan/@Category


Bibliografía (https://www.php.net/manual/en/refs.xml.php)

Entorno Linux Ubuntu 18.04

Instalar composer:
sudo apt install composer

Instalar php7.2:
sudo apt-get update
sudo apt-get install php7.2
sudo apt-get install php-xml
sudo apt install openssl php-common php-curl php-json php-mbstring php-mysql php-xml php-zip


Instalar proyecto:
composer create-project symfony/framework-standard-edition parse_xml "2.8.*"

Iniciar el servidor en symfony2.8:
php app/console server:start

Para cada paseado:
1. Cargar datos. Manual/Popup 
2. Leer los datos.
3. Obtener los datos.
4. Calcular la eficiencia.




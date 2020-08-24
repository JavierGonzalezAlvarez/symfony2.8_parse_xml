
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

Instalar proyecto:
composer create-project symfony/framework-standard-edition parse_xml "2.8.*"

Iniciar el servidor en symfony:
php app/console server:start

1. Cargar datos. Servicio compartido por todas las librerías.
2. Leer los datos.
3. Obtener los datos
4. Calcular la eficiencia




# 🎨 Simple Drawing Tool

Bienvenido al proyecto Simple Drawing Tool desarrollado en Symfony 🚀

---

## 🏛️ Arquitectura en Capas

El proyecto utiliza una arquitectura de capas separando responsabilidades claramente:

- **Presentación (`Presentation\Command`)**: Recibe comandos del usuario a través de la consola.
- **Aplicación (`Application\Service`)**: Contiene los servicios que coordinan las operaciones de la lógica de negocio.
- **Dominio (`Domain\Model`, `Domain\Repository`, `Domain\Exception`)**: Modela el negocio puro, define entidades, repositorios e invariantes.
- **Infraestructura (`Infrastructure\File`)**: Implementa detalles técnicos como el guardado de archivos.

Cada capa sólo puede comunicarse con las capas inferiores.

---

## 🛠️ Patrones de Diseño Utilizados

- **Command Pattern**: Usado en `DrawingCommand` para encapsular la entrada del usuario como un objeto de comando.


---

## 🚀 Ejecución

php bin/console app:draw input.txt output.txt

---

## 🧪 Pruebas

./vendor/bin/phpunit


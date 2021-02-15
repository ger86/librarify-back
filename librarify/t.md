# Bloque 1

😎 ¡Estamos de vuelta! Es decir, toca engrasar todos nuestros conocimientos de React para continuar profundizando en nuestra librería favorita. Este **primer ejercicio** tiene ese objetivo: repasar todo lo que aprendiste durante Latte and Front y proponerte un pequeño reto para que sigas cogiendo soltura cuando trabajes con esta librería. 

¡Vamos allá!

## Ejercicio 1

Es el momento de exprimir la API que estamos consumiendo para añadir nuevas características a nuestra aplicación:

https://documenter.getpostman.com/view/9227839/TVmV6EQX

Para este primer ejercicio quiero que realices las siguientes tareas:

1. Añadir a la vista de un libro toda la nueva información disponible: descripción, puntuación (sus posibles valores van del 1 al 5 y estaría genial que se viera como estrellas ⭐️), autores (un libro puede haber sido escrito por varios autores aunque no sea lo habitual), y fecha de lectura en el caso de que exista.

2. Si te apetece en el listado de libros puedes también mostrar algunos de estos nuevos campos (no te vengas arriba, la descripción no hace falta mostrarla en el listado 🥸).

3. También será necesario retocar el formulario para que el usuario pueda completar todos estos campos. A continuación te dejo cómo deben ser los nuevos campos:

    1. Descripción (no requerido): un textarea o un editor WYSIWYG.

  1. Puntuación (no requerido). Podríamos hacer un input de tipo número limitado del 1 al 5 pero no es lo que mola, ¿no? Mejor implementa un campo personalizado que pinte 5 estrellas y que el usuario pueda seleccionar la puntuación pulsando sobre la estrella que representa la puntuación. Aquí no vale usar librerías como [React Input Star](https://github.com/ikr/react-star-rating-input) pero puedes ver su código para inspirarte.

  2. Fecha de lectura (no requerido). Aquí sí vale usar una librería que te permita seleccionar la fecha que representará cuando el usuario se terminó el libro. Yo en su momento usé [React Date Picker](https://www.npmjs.com/package/react-date-picker) pero puedes usar cualquier otra. 

  3. Autores (requerido, al menos 1). Los autores funcionan igual que las categorías: se pueden crear al vuelo o seleccionar uno de los ya existentes (por tanto, habrá que usar el endpoint /authors para recuperar los autores que ya tenemos disponibles).

  4. Edición de un libro. Cuando el usuario quiera editar un libro tendrá que ver todos los campos con su valor actual y poder modificar el valor de cada uno de ellos. Recuerda que [editar categorías](https://d15ryumh8qi43u.cloudfront.net/8-1-proyecto-final) tenía algo de chicha por cómo espera la API recibir los valores. Esta complejidad también la tendrá el campo "Autores" del formulario, ya que lo implementé del mismo modo. Igual puedes reutilizar de algún modo la lógica para no tener un "copy-paste" de ambos campos.

4. Añade una vista a la aplicación que te permita ver las categorías, editarlas y borrarlas (de momento la API no devuelve los libros que tiene asociados una categoría).

5. Añade una vista a la aplicación que te permita ver los autores, editarlos y borrarlas (de momento la API no devuelve los libros que tiene asociados una categoría).

  5.1 Formulario de crear categoría. Vamos a practicar un poco aquí y en vez de crear un formulario normal vamos a usar [Formik](https://formik.org/docs/overview). 

6. En la lista de libros añade una opción de filtrar por autor del mismo modo que hacemos con las categorías. 

Y... ¡ya está Había pensado en añadir una funcionalidad muy chula pero, ¿qué te parece si la dejamos para el siguiente bloque? Tengo muchas ganas de que la implementes así que, coge fuerzas, dale cariño y prepárate para todo lo que está por venir: ¡despegamos! 🚀

## Recursos útiles

* ¿Un editor **WYSIWYG**? ¿Qué es es eso? Ese término es el acrónimo de What You See Is What You Get, y básicamente es un editor de texto incrustado como tenemos en Wordpress, Google Docs o Microsoft Word. Existen numerosas librerías de front para implementarlo, como por ejemplo [Draft JS](https://draftjs.org/) o [TinyMCE](https://github.com/tinymce/tinymce-react). Si descubres una más molona que las dos anteriores anímate a usarlas.

* ¿Formik? WTF. Formik como te conté en Latte and Front es una librería para crear formularios sin repetir código y para tener funcionalidad tan interesante como validación o información en tiempo real del formulario: errores en los campos, el estado de los campos (es decir, si han sido `touched` o no), etc. No es una librería difícil de usar, pero prefiero que la aprendas a usar poco a poco y por eso he escogido que tan sólo la metas dentro del formulario de crear (editar) categoría. Por eso te aconsejo que sigas el tutorial de su propia documentación https://formik.org/docs/tutorial y como complemento me veas hablando de ella (momento autopromo) en el siguiente vídeo:

https://youtu.be/3zSgzaPp1gg

¡Verás lo fácil que es usarla!

** ❗️ Pssss. Si trasteas por la documentación de la librería descubrirás que puedes usar Formik de dos formas: como Componente `<Formik>` y con el hook `useFormik`. Te recomiendo que uses la primera, ya que resulta más intuitiva y cuando ya te sientas cómodo pases a usar el hook. 

** El componente `<Formik>` se basa en un patrón de React llamado Render Props que es muy útil para aislar funcionalidad y evitar repetir código. Puedes echarle un ojo en la propia documentación: https://es.reactjs.org/docs/render-props.html o en este artículo: https://medium.com/@osmancea/entendiendo-render-props-en-react-dfe22f84f593.

## Dudas

Recuerda que puedes dejar tus dudas en la siguiente hoja de Google Drive para que pueda ir respondiéndolas:

https://docs.google.com/spreadsheets/d/1f9OFbM-Yg19Fxl1jU21mL0BMWPfgJVdsT-8jES2KAhk/edit?usp=sharing

💛 ¡A por el primer bloque!





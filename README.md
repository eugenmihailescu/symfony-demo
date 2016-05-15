## Symfony Demo

 Symfony Demo is a demonstrative application that I created while I read about what is Symfony Framework and how it works.

It should be regarded as an working frame which allowed me to test|implement various bundles|modules of Symfony. Below is a short list of Symfony bundles that were used and a brief description of what they do and why they were used.

- [Security](http://symfony.com/doc/current/book/security.html) 
- [Controller](http://symfony.com/doc/current/book/controller.html)
- [Routing](http://symfony.com/doc/current/book/routing.html)
- [Templating](http://symfony.com/doc/current/book/templating.html)
- [Doctrine](http://symfony.com/doc/current/book/forms.html)
- [Forms](http://symfony.com/doc/current/book/forms.html)
- [Validation](http://symfony.com/doc/current/book/validation.html)
- [Translation](http://symfony.com/doc/current/book/translation.html)
- [Services](http://symfony.com/doc/current/book/service_container.html) 

### Security

This bundle aids in defining an authentication and authorization layer with ease. 
You start by setting a firewall for your application which encloses various components:
- a security controller that should provide a login|logout form
- the security storage provider that tells where|how the users and their respective credentials are stored, what encryption encoding to use
- firewall rules definitions for each implemented environments (ie. `dev`, `prod`, etc):
  - the routes/folders/files that can be accessed without authorization (eg. images, css, js)
  - if anonymous access is allowed or not, etc
- an authorization layer (Voter) that allows you to implement a fine grained role-based authorization mechanism for application users (eg. `ADMIN` is allowed here and there while a regular `USER` is restricted in certain sectors).

> This application defines an in-memory security provider with two predefined users which have password encrypted with BCrypt algorithm. It also defines a custom login form and a fine grained authorization layer (a Voter) that handles the authorization to certain routes based on user predefined roles.

### Controller

This bundle allows you to process the HTTP requests and return a HTTP response (in various formats like HTML|XML|JSON,etc). A response can be sent as the result of a raw `echo` call or by rendering a (Twig|Blade|PHP,etc) PHP template page.

> All the screens that this application renders are the response of a specific controller specifically written to deliver that screen content.

### Routing

This bundle allows you mapping various URL routes patterns (eg. `/about` or `/get/this/route`) and the specific controllers responsible for delivering these resposes.

> This application defines 10+ different routes that delegates the HTTP request to different controllers.

### Templating

This bundle allows you to define and use templates (using various templating engine like Twig, PHP, Blade or even your custom written engine) that are used to render the content which a controller will deliver as a response to a HTTP request.

> This application defines 14+ different Twig templates that aids in rendering the response sent by the controllers.

### Doctrine

This bundle aids in abstraction of the database layer by providing means to define, populate and access database data:
- the database tables becomes PHP entity classes
- the tables' columns becomes PHP class properties
- the tables' indexes and metadata becomes PHP classes|properties annotations

> For instance this applications defines 10+ different PHP classes (entities) that represents the tables of a tipical WordPress database. By using class|property comment annotations we define their metadata like the table name, the table primary key, the column name and data type, the column index, unique index or foreign keys, etc. Using these class definitions Symfony can automatically create the physical database no mater what driver you might want to use (SQLite in our example).

### Forms

This bundle aids in generating automatically the forms for a specific entity (eg. a table) and you to handle the form requests|submission (eg. saving the form data into the entity, ie. database table).

> This application uses Forms to generate automatically the form (in readonly|read-write mode) for a chosen entity and to save the form data to the database.

### Validation

Every entity defined earlier (see Doctrine) has defined one|more constraints like a unique index, a not null column, etc. This bundle makes sure that the data are validated at a higher level (Symfony|PHP) before the entity data is saved into the database.

> This application defines for each entity various constraints that must be meet. When a constraint is not meet a specific error message is automatically shown near the field that violated that constraint and as such the form data is not saved to the database.

### Services

Symfomy provides various built-in services which may be used almost at any level of your application (eg. translation). Besides these you can create your own services that provides a specific functionality.
> This application defines various services like language detector, foreign key validator, exception listener, a security voter and even a Twig extension that extends the built-in Twig functions and filters.
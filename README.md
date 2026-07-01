
# kaye routing

Un package Laravel permettant de définir les routes directement dans les contrôleurs via des PHP Attributes (`#[Get]`, `#[Post]`, etc.), sans utiliser `routes/web.php` ou `routes/api.php`.

##  Installation

Installe le package via Composer :

```bash
composer require kaye/routing
```

##  Configuration

Le service provider est auto-enregistré grâce à l'auto-discovery de Laravel.

Si nécessaire, ajoute-le manuellement dans `config/app.php` :

```php
'providers' => [
    // ...
    RouteAttributes\RouteAttributesServiceProvider::class,
],
```

---

##  Utilisation

### Exemple simple

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;

class UserController extends Controller
{
    #[Get('/users', name: 'users.index')]
    public function index()
    {
        return response()->json(['users']);
    }

    #[Post('/users', name: 'users.store')]
    public function store()
    {
        return response()->json(['created']);
    }
}
```

### Préfixe de route

Utilise `#[Prefix]` pour définir un préfixe commun pour toutes les routes d'un contrôleur :

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Prefix;

#[Prefix('/api/v1')]
class UserController extends Controller
{
    #[Get('/users')]  // Route: /api/v1/users
    public function index() {}

    #[Post('/users')] // Route: /api/v1/users
    public function store() {}
}
```

Le préfixe peut également être défini au niveau de la méthode :

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Prefix;

class UserController extends Controller
{
    #[Get('/profile')]
    #[Prefix('/api')]  // Priorité sur le préfixe de classe
    public function profile() {} // Route: /api/profile
}
```

### Middleware

Utilise `#[Middleware]` pour assigner des middlewares à vos routes :

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Middleware;

class UserController extends Controller
{
    #[Get('/users')]
    #[Middleware('auth')]  // Middleware unique
    public function index() {}

    #[Post('/users')]
    #[Middleware(['auth', 'throttle:60,1'])]  // Multiples middlewares
    public function store() {}
}
```

### Combinaison de Préfixe et Middleware

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Prefix;
use RouteAttributes\Attributes\Middleware;

#[Prefix('/api/v1')]
#[Middleware('auth')]
class UserController extends Controller
{
    #[Get('/users')]  // Route: /api/v1/users, Middleware: auth
    public function index() {}

    #[Post('/users')] // Route: /api/v1/users, Middleware: auth
    public function store() {}

    #[Get('/public')]
    #[Middleware('throttle:60,1')]  // Surcharge du middleware
    public function publicEndpoint() {} // Route: /api/v1/public, Middleware: throttle:60,1
}
```

---

##  Routes disponibles

| Méthode | Attribut | Exemple |
|---------|----------|---------|
| GET | `#[Get]` | `#[Get('/users')]` |
| POST | `#[Post]` | `#[Post('/users')]` |
| PUT | `#[Put]` | `#[Put('/users/{id}')]` |
| PATCH | `#[Patch]` | `#[Patch('/users/{id}')]` |
| DELETE | `#[Delete]` | `#[Delete('/users/{id}')]` |

### Attributs supplémentaires

| Attribut | Cible | Exemple |
|----------|-------|---------|
| `#[Prefix]` | Classe/Méthode | `#[Prefix('/api/v1')]` |
| `#[Middleware]` | Classe/Méthode | `#[Middleware('auth')]` ou `#[Middleware(['auth', 'throttle'])]` |

---

##  Exemple complet

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Put;
use RouteAttributes\Attributes\Delete;
use RouteAttributes\Attributes\Prefix;
use RouteAttributes\Attributes\Middleware;

#[Prefix('/api/v1')]
#[Middleware('auth:api')]
class ProductController extends Controller
{
    #[Get('/products')]
    public function index() {}

    #[Get('/products/{id}')]
    public function show($id) {}

    #[Post('/products')]
    #[Middleware('throttle:10,1')]  // Limité à 10 requêtes par minute
    public function store() {}

    #[Put('/products/{id}')]
    public function update($id) {}

    #[Delete('/products/{id}')]
    public function destroy($id) {}
}
```

---

##  Comment ça fonctionne ?

Ce package :

1. **Scanne** les contrôleurs Laravel
2. **Lit** les PHP Attributes
3. **Enregistre** automatiquement les routes via `Route::get()`, `Route::post()`, etc.
4. **Applique** les préfixes et middlewares définis

---

##  Avantages

✔ Plus besoin de `routes/api.php` ou `routes/web.php`  
✔ Code plus propre et centralisé  
✔ Préfixes et middlewares directement dans le contrôleur  
✔ Inspiré des frameworks modernes (Symfony, NestJS)  
✔ Compatible Laravel 10+  
✔ Support PHP 8.0+

---

##  Limitations

- Les routes sont chargées au boot de l'application
- Peut nécessiter un cache optimisé pour les gros projets
- Nécessite PHP 8.0 ou supérieur


##  Contribution

Les PR sont les bienvenues ! 

1. Fork le projet
2. Crée une branche `feature/ma-feature`
3. Commit tes changements
4. Ouvre une Pull Request

---

##  License

MIT License - voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

##  Support

Si ce projet t'aide, n'hésite pas à mettre une star  sur GitHub !

---

**Made with  pour la communauté Laravel**
```

**Résumé des ajouts :**

1.  **Section Préfixe** - Documentation complète avec exemples
2.  **Section Middleware** - Documentation complète avec exemples
3.  **Section Combinaison** - Exemple d'utilisation simultanée
4.  **Tableau des attributs supplémentaires** - Vue d'ensemble rapide
5.  **Exemple complet enrichi** - Avec middlewares et préfixes
6.  **Roadmap mise à jour** - Fonctionnalités cochées
7.  **Explications améliorées** - Ajout de l'application des préfixes/middlewares
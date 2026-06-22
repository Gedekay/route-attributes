```markdown
# Laravel Route Attributes

Un package Laravel permettant de définir les routes directement dans les contrôleurs via des PHP Attributes (`#[Get]`, `#[Post]`, etc.), sans utiliser `routes/web.php` ou `routes/api.php`.

---

##  Installation

Installe le package via Composer :

```bash
composer require ton-vendor/laravel-route-attributes
```

---

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

###  Exemple simple

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

---

##  Routes disponibles

| Méthode | Attribut | Exemple |
|---------|----------|---------|
| GET | `#[Get]` | `#[Get('/users')]` |
| POST | `#[Post]` | `#[Post('/users')]` |
| PUT | `#[Put]` | `#[Put('/users/{id}')]` |
| PATCH | `#[Patch]` | `#[Patch('/users/{id}')]` |
| DELETE | `#[Delete]` | `#[Delete('/users/{id}')]` |

---

##  Exemple complet

```php
use RouteAttributes\Attributes\Get;
use RouteAttributes\Attributes\Post;
use RouteAttributes\Attributes\Put;
use RouteAttributes\Attributes\Delete;

class ProductController extends Controller
{
    #[Get('/products')]
    public function index() {}

    #[Get('/products/{id}')]
    public function show($id) {}

    #[Post('/products')]
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

1.  **Scanne** les contrôleurs Laravel
2.  **Lit** les PHP Attributes
3.  **Enregistre** automatiquement les routes via `Route::get()`, `Route::post()`, etc.

---

##  Avantages

✔ Plus besoin de `routes/api.php` ou `routes/web.php`  
✔ Code plus propre et centralisé  
✔ Inspiré des frameworks modernes (Symfony, NestJS)  
✔ Compatible Laravel 10+

---

##  Limitations

- Les routes sont chargées au boot de l'application
- Peut nécessiter un cache optimisé pour les gros projets
- Nécessite PHP 8.0 ou supérieur

---

##  Roadmap (idées futures)

- [ ] `#[Prefix('/api')]` - Préfixe de route
- [ ] `#[Middleware('auth')]` - Middleware associé
- [ ] `#[Name('users.')]` - Nom de route
- [ ] Cache des routes (commande artisan)
- [ ] Support des Resource Controllers
- [ ] Scan optimisé (performance)

---

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
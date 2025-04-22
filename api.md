# 📚 Documentation de l’API – app_citations

## 🔒 Authentification

### `POST /api/utilisateurs/register`
> Crée un nouvel utilisateur.  
**Body (JSON)** :
```json
{
  "email": "user@example.com",
  "password": "password123",
  "name": "Dupont",
  "surname": "Jean"
}
```

### `POST /api/utilisateurs/login`
> Connecte un utilisateur et retourne un JWT.  
**Body (JSON)** :
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```
**Response (JSON)** :
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR..."
}
```

## 👤 Utilisateurs

### `GET /api/utilisateurs/{id}`
> Récupère les infos d’un utilisateur (protégé par AuthMiddleware).

### `PUT /api/utilisateurs/{id}`
> Met à jour un utilisateur (protégé, accès à soi uniquement).  
**Headers** :
```
Authorization: Bearer <token>
```

### `DELETE /api/utilisateurs/{id}`
> Supprime un utilisateur (protégé).

## 🧠 Citations

### `POST /api/citations`
> Ajoute une citation.  
**Headers** :
```
Authorization: Bearer <token>
```
**Body (JSON)** :
```json
{
  "contenu": "Ceci est une citation",
  "categorie_id": 1
}
```

### `GET /api/citations`
> Liste toutes les citations.

### `GET /api/citations/{id}`
> Récupère une citation par son ID.

### `PUT /api/citations/{id}`
> Modifie une citation (protégé + vérifie l’auteur).

### `DELETE /api/citations/{id}`
> Supprime une citation (protégé + vérifie l’auteur).

### `GET /api/citations/utilisateur/{id}`
> Liste les citations d’un utilisateur donné.

### `GET /api/citations/categorie/{id}`
> Liste les citations d’une catégorie.

### `POST /api/citations/{id}/like`
> Ajoute un like à une citation.

### `POST /api/citations/{id}/vue`
> Incrémente le compteur de vues d’une citation.

## 📁 Catégories

### `POST /api/categories`
> Crée une nouvelle catégorie.

### `GET /api/categories`
> Liste toutes les catégories.

### `PUT /api/categories/{id}`
> Modifie une catégorie.

### `DELETE /api/categories/{id}`
> Supprime une catégorie.

## ⭐ Préférences

### `POST /api/preferences`
> Ajoute une préférence (utilisateur/catégorie).  
**Body (JSON)** :
```json
{
  "utilisateur_id": 1,
  "categorie_id": 2
}
```

### `DELETE /api/preferences/{id}`
> Supprime une préférence.

### `GET /api/preferences/utilisateur/{id}`
> Liste les catégories préférées d’un utilisateur.

## 🛡️ Sécurité & Auth Middleware

- Toutes les routes sensibles utilisent `JwtMiddleware` pour vérifier le token.
- Les routes utilisateurs (GET/PUT/DELETE `/api/utilisateurs/:id`) sont protégées avec `AuthMiddleware`.
- Les modifications de citation sont restreintes à l’auteur avec un middleware ou une vérification dans le contrôleur.

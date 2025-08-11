#  Mini plateforme pour partager et recevoir des Citations.

1. Installation

- composer install
- php -S localhost:8000 -t public


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

## Utilisateurs

# Recuperer les infos de l'utilisateur
`GET api/utilisateurs/{id}`
**Response (JSON)** :
```json
{
    "id": 25,
    "email": "filskiemde13@gmail.com",
    "name": "kiemde",
    "surname": "lucien",
    "createdAt": {
        "date": "2025-04-26 12:22:54.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updatedAt": null
}
```
# Mise à jour 
`PUT api/utilisateurs/{id}`
**Body (JSON)** :
```json
{
    "name": "kiemde",
    "surname": "lucien",
    "email": "filskiemde13@gmail.com",
    "password": "lucien"
}
```

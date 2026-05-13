# TechMada RH
## Instruction 
Pour le fichier .env, il faut changer le chemin absolue vers le database.db selon votre propre chemin.
## Lancements et preparation
### Base et donnees
Le depot utilise SQLite:
- fichier: `writable/database.db`

Seeder principal:

```bash
php spark db:seed MainSeeder
```

### Demarrage local

```bash
php spark serve --host=0.0.0.0 --port=8080
```

Base URL actuelle:
- `http://localhost:8080/`

## 9. Comptes de demonstration

- `admin@techmada.mg / admin123`
- `rh@techmada.mg / rh123`
- `employe@techmada.mg / emp123`
- `tsiry@techmada.mg / emp123`
- `haja@techmada.mg / emp123`
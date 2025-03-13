# Park-It

Park-It est un api pour la gestion des parkings. Elle permet aux utilisateurs de réserver des places de parking, de gérer les abonnements et de suivre l'occupation des parkings en temps réel.

## Fonctionnalités

- Réservation de places de parking
- Gestion des abonnements
- Suivi de l'occupation des parkings en temps réel

## Installation

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/votre-utilisateur/park-it.git
    ```
2. Accédez au répertoire du projet :
    ```bash
    cd park-it
    ```
3. Installez les dépendances :
    ```bash
    composer install
    npm install
    ```
4. Configurez l'environnement :
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. Lancez le serveur de développement :
    ```bash
    php artisan serve
    npm run dev
    ```

## Utilisation

Accédez à l'application via `http://localhost:8000` et commencez à utiliser Park-It pour gérer vos parkings.

## Api Documentation
Acceder au url suivant pour afficher une documnetation detaillé sur chaque endpoint dans le projet
```
http://127.0.0.1:8000/api/documentation
```

## Contribution

Les contributions sont les bienvenues ! Veuillez soumettre une pull request ou ouvrir une issue pour discuter des changements que vous souhaitez apporter.

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

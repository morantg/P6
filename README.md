# P6

#Installation

- Clonez ou téléchargez le repository Github dans votre machine local : 
    git clone https://github.com/morantg/p6

- Configurez vos variables d'environnement dans le fichier .env (variable DATABASE_URL)

- Installez composer : composer install

- Créez la base de données : php bin/console doctrine:database:create

- Créez les tables dans votre base de données en appliquant les migrations :
    php bin/console doctrine:migrations:migrate

- Installez KnpPaginator pour la pagination du site :
    composer require knplabs/knp-paginator-bundle     

# AdopteUnDev

une application web permettant aux développeurs et aux entreprises de créer des profils ou des fiches
de postes et de les faire correspondre selon un modèle inspiré des sites de rencontres. L'objectif est
d'optimiser la mise en relation entre développeurs et recruteurs via des fonctionnalités conviviales et
innovantes.




## Installation

1. Télécharger le projet en clonant

```bash
  git clone https://github.com/misterOumar/projet_adopte_un_dev.git
```

2. Lancer votre application docker desktop
3. Ouvrir votre projet cloner avec votre éditeur de code
4. Démarrer vos container docker
```bash 
docker-compose up -d
```
4. Accéder au conteneur PHP et entrer dans le projet : 
```bash 
docker exec -it symfony_app bash
cd adopteundev
```
5. Installer le déendances du projet avec composer
```bash 
composer install
```
6. demarrer le projet:
```bash 
symfony server:start
```
7. Normalement si tout se passe bien vous devez voir une page de coming soon 😎

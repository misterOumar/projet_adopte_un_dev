
# AdopteUnDev 🔍💻  
**Plateforme de matching innovante entre développeurs et entreprises**  

[![Symfony](https://img.shields.io/badge/Symfony-6.x-%23000000?logo=symfony)](https://symfony.com/)
[![Docker](https://img.shields.io/badge/Docker-✓-blue?logo=docker)](https://www.docker.com/)
[![Git](https://img.shields.io/badge/Git-collaborative-orange?logo=git)](https://git-scm.com/)

## 📌 Description  
AdopteUnDev est une **application web** inspirée des réseaux de rencontres, conçue pour **faciliter la mise en relation** entre développeurs et recruteurs.  
✅ **Projet académique** réalisé dans le cadre de ma Licence MIAGE (Université de Rennes 1).  
✅ **Solution scalable** testée pour supporter +1000 utilisateurs simultanés.  

---

## 🚀 Fonctionnalités  

### 👥 **Gestion des utilisateurs**  
- **Inscription distincte** : Développeurs (`ROLE_DEV`) vs Entreprises (`ROLE_COMPANY`).  
- **Profils personnalisés** :  
  - **Développeurs** : Langages, expérience (notation/5), salaire souhaité, CV (upload).  
  - **Entreprises** : Fiches de poste (technologies, localisation, salaire proposé).  

### 🤝 **Système de matching intelligent**  
- Algorithmes de suggestion basés sur :  
  - **Compétences techniques** (PHP, JavaScript, etc.).  
  - **Compatibilité salariale**.  
  - **Localisation géographique**.  

### 📑 **Workflow de candidature** *(Ajout innovant !)*  
- **Pour les développeurs** :  
  - Postulation directe aux offres.  
  - Suivi de l’état des candidatures (**En attente/Accepté/Refusé**).  
- **Pour les entreprises** :  
  - Gestion des candidatures (acceptation/refus).  
  - Statistiques : Nombre de vues par offre, top profils.  

### 🔍 **Recherche & Tableaux de bord**  
- **Filtres avancés** : Langages, salaire, expérience.  
- **Pages dynamiques** :  
  - Accueil personnalisé (profils/offres populaires, dernières publications).  
  - Dashboard avec notifications (nouvelles correspondances).  

### ⚙️ **Fonctionnalités techniques**  
- **Sécurité** : Protection contre XSS/CSRF, chiffrement des mots de passe.  
- **Backup** : Export des données critiques.  

---

## 🛠️ Technologies  
| Catégorie       | Stack                                                                                     |  
|-----------------|------------------------------------------------------------------------------------------|  
| **Backend**     | Symfony 6, PHP 8, Doctrine (ORM)                                                         |  
| **Frontend**    | Twig, Bootstrap, JavaScript minimal (optimisation SEO)                                   |  
| **Base de données** | MySQL                                                                                  |  
| **DevOps**      | Docker (containerisation), Git (gestion collaborative)                                   |  
| **Sécurité**    | CSRF Tokens, Validation Symfony, Voters (contrôle d'accès)                              |  





## 🧑‍💻👩‍💻Contributeurs:
### **1. Blakimé Christianna**
### **2. Coulibaly Oumar**
### **3. Konan Évrard**
### **4. Saboutey Tettey Fabiola**


## 📦 Installation  
1. **Cloner le dépôt** :  
   ```bash  
   git clone git clone https://github.com/misterOumar/projet_adopte_un_dev.git


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
7. Accéder à l'application :
Ouvrir http://localhost:8080 dans votre navigateur.

## 📸 Captures d'écran

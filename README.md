# 🎰 Projet "Hellcase"

## 📝 Brief du Projet

L'objectif est de faire un site de gambling Counter Strike un peu comme "Hellcase". Il faut qu'il y ai: 
- [ ] une liste de caisses disponibles
- [ ] le contenu de chaque caisse
- [ ] le profil de l'utilisateur connecté son solde et son inventaire
- [ ] son historique d'ouverture
- [ ] la possibilité d'ouvrir une caisse
- [ ] une gestion des abonnements

---

## 🎯 Objectifs & Fonctionnalités Clés

L'objectif est de créer une API qui gère l'ensemble de l'écosystème "Hellcase".

* **API Complète (CRUD) :** Fournir des endpoints générés automatiquement par **API Platform** pour gérer les entités de base (Caisses, Items, Utilisateurs).
* **Authentification Sécurisée :** Utiliser les **Tokens JWT** (`lexik/jwt-authentication-bundle`) pour sécuriser l'API. L'application gère l'inscription (`/api/register`) et la connexion (`/api/login_check`) des utilisateurs.
* **Logique Métier :** Implémenter la logique d'ouverture de caisse comme une opération API personnalisée (`POST /api/cases/{id}/open`), garantissant que toute la logique de tirage ("roll") et de transaction de solde est gérée côté serveur.
* **Sécurité Granulaire (Voters) :** Utiliser les **Voters Symfony** pour gérer des règles d'autorisation. Cela inclut :
    * La propriété des ressources (un utilisateur ne peut voir que son propre inventaire).
    * **La gestion des offres** : Restreindre l'accès à certaines caisses ou fonctionnalités en fonction du "tier" de l'utilisateur (ex: `basique`, `premium`, `diamant`).
* **Tâches Planifiées (Crons) Optionnel :** Utiliser **Symfony Messenger** pour gérer les tâches asynchrones et planifiées, notamment :
    * L'envoi d'emails transactionnels (confirmation d'achat, etc.).
    * La génération de rapports de **statistiques** (ex: revenus quotidiens).

---

## 🗺️ Roadmap

Voici les grandes étapes.s

### ✅ Phase 1 : MVP
* **Auth :** Inscription / Connexion JWT.
* **Entités :** `User`, `Kase`, `Item`, `InventoryItem`, `KaseItem`.
* **API :** CRUD pour les Caisses/Items (lecture seule).
* **Logique :** Opération `POST /api/kase/{id}/open` fonctionnelle (solde fictif).
* **Front :** Pages de base (login, liste des caisses, inventaire). **(Optionnel)**

### 🚀 Phase 2 : Securité
* **Sécurité (Voters) :** Implémentation des **Voters** pour :
    * Protéger l'inventaire (`InventoryItemVoter`).
    * **Gérer les tiers (`OfferVoter`)** : Création des offres `premium` / `diamant` et restriction d'accès aux caisses associées.

### 📈 Phase 3 : Crons
* **Tâches Cron (Messenger) :**
    * Mise en place des **crons** de stats.

### 🌌 Phase 4 : Partie Bonus
* **Vente d'Items :** Opération `POST /api/inventory_item/{id}/sell` (revente contre solde).
* **Trade-up :** Opération `POST /api/trade-up` (échanger 10 items contre 1 de rareté supérieure).

---

## 🛠️ Stack Technique Principale

* **PHP** / **Symfony**
* **API Platform**
* **Doctrine ORM** / **PostgreSQL**
* **LexikJWTAuthenticationBundle** (Authentification)
* **Symfony Messenger** (Crons & Tâches de fond)



# 🚀 Démarrage du Projet

1. Démarrer les conteneurs Docker :
```bash
docker compose up --wait
```
2. Ouvrir le shell php : 
```bash
docker compose exec php bash
```
3. Load les fixtures :
```bash
php bin/console doctrine:fixtures:load
```
4. Lancer le front :
```bash
npm run dev
```

Module Prestashop MY FLYING BOX
===============================

Module prestashop fournissant une interface pour les services de transport expresse fournis par MY FLYING BOX (société française, voir http://www.myflyingbox.com).

## Présentation

Ce module fournit deux ensembles de fonctionnalités indépendants :
- la commande d'expéditions à travers l'API MY FLYING BOX par une interface back-office dédiée
- le calcul automatisé des coûts de transport pour le panier d'un client, au moment de la commande

## Installation

Pour utiliser ce module, vous avez besoin de :
- Prestashop 1.7+, installé et fonctionnel
- le module php-curl activé sur le serveur
- un compte MY FLYING BOX actif et les clés d'API correspondantes

Ce module n'est pas compatible avec les versions de Prestashop antérieures à la 1.7.

### Installation à partir des sources

Placez-vous dans le répertoire /modules de votre instance Prestashop (remplacez PS_ROOT_DIR par le chemin de votre instance Prestashop) :
```
cd PS_ROOT_DIR/modules
```

Clonez le code du module à partir de notre dépôt Github (note : le répertoire du module DOIT être lowcostexpress) :

```bash
git clone --recursive https://github.com/myflyingbox/prestashop-lce.git lowcostexpress
```

Une des bibliothèques externes du module (php-lce) a des dépendances spécifiques, que vous devez initialiser explicitement avec 'composer' :

```bash
cd lowcostexpress/lib/php-lce
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Si vous installez le module sur une version de PHP antérieure à la 8.0, faites la même chose sur la version historique de la bibliothèque :

```bash
cd lowcostexpress/lib/php-lce-0.0.3
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

### Installation à partir d'un paquet

Ouvrez la [liste des publications](https://github.com/lce/prestashop-lce/releases) et téléchargez le dernier paquet disponible (premier de la liste).
Vous pouvez ensuite charger ce fichier tel quel dans le gestionnaire de modules de Prestashop.

## Configuration

### Installez/activez le module

Ouvrez la page de gestion des modules dans Prestashop, et installez le module, qui devrait ensuite être listé sous le nom 'MY FLYING BOX'. Vous pouvez ensuite ouvrir directement la page de configuration.

### Configurez les paramètres du module

Les paramètres suivants peuvent être définis sur la page de configuration du module :
* Identifiant et mode de passe de votre compte API MY FLYING BOX
* l'environnement d'API à utiliser (staging ou production)
* informations par défaut pour l'expéditeur des colis (vous)
* règles de calcul pour l'évaluation automatique des coûts de transport lors de la commande d'un panier. Soyez particulièrement vigilants lorsque vous spécifiez les données de correspondance entre les poids et les dimensions de colis : le module ne peut pas deviner les dimensions de votre emballage final pour un panier donné, et basera donc ses calculs uniquement sur le poids total du panier et le tableau de correspondance que vous allez renseigner dans la configuration.
* votre clé d'API Google Cloud, nécessaire pour l'affichage d'une carte de sélection des points relais.

Si vous souhaitez que vos clients puissent sélectionner un service de transport MY FLYING BOX (avec calcul automatique du prix), vous devrez initialiser les services MY FLYING BOX en tant que transporteurs dans Prestashop.
La page de configuration vous permet d'initialiser/mettre à jour automatiquement la liste des produits de transport disponibles pour votre/vos pays d'expédition de marchandise.

Notez que vous n'avez pas besoin d'initialiser les transporteurs si vous ne souhaitez utiliser que les fonctionnalités back-office du module LCE. Lorsque vous solliciterez des offres de transport pour votre expédition, toutes les offres disponibles vous serez systématiquement proposées.

## Utilisation

### Fonctionnalités front-office (perspective client)

#### Frais de livraison

Lorsqu'un client procède à la commande de son panier, des options de transports seront proposées dynamiquement basé sur les offres récupérées depuis l'API MY FLYING BOX et les règles de calcul que vous aurez spécifié sur la page de configuration du module.

Le client peut ensuite sélectionner l'une de ces offres, et sera facturé le montant calculé.

#### Suivi

Lorsqu'une commande est expédiée par MY FLYING BOX (que le client ait ou non lui-même sélectionné un transport MY FLYING BOX lors de la commande), les informations de suivi sont accessibles par le client en temps réel sur le récapitulatif complet de la commande.

### Back-office

Sur la page de gestion des commandes (en back-office), une nouvelle zone 'Expéditions MY FLYING BOX' est affichée. A partir de ce cadre, vous pouvez initier une nouvelle expédition.

Chaque expédition MY FLYING BOX a sa propre page de gestion, sur laquelle vous pouvez :
* modifier/corriger l'adresse d'enlèvement et de livraison ;
* saisir la liste de colisage, avec les dimensions détaillées, le poids, les informations douanières et les références ;
* obtenir les offres de transports proposée par l'API MY FLYING BOX, avec les tarifs et conditions ;
* commander un transport à partir de l'API MY FLYING BOX ;
* télécharger les étiquettes colis fournies par le transporteur, à aposer sur les colis avant enlèvement/dépôt ;
* suivre l'avancement de l'expédition.

## Support

Ce module est maintenu directement par les développeurs de l'API MY FLYING BOX. Vous pouvez nous contacter à l'adresse <tech@myflyingbox.com> si vous avez besoin d'aide pour l'utilisation, le paramétrage ou l'installation du module sur votre instance Prestashop. Si vous n'avez pas encore de compte MY FLYING BOX ou de clés d'API, envoyez un message à <info@myflyingbox.com> et vous serez recontacté par notre équipe commerciale.

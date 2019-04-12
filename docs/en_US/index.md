Plugin permettant de gérer des poeles ou chaudière à granulés 

Configuration du plugin
=======================

Après installation du plugin, il vous suffit de l’activer.


Configuration des équipements
=============================

Equipement
----------

Ici vous avez les informations principales de votre appareil :

-   **Nom de l’équipement** : nom de votre équipement 

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement

-   **Activer** : permet de rendre votre équipement actif

-   **Visible** : le rend visible sur le dashboard

-   **Adresse IP Direct** : l’adresse IP local de votre appareil

-   **Port Local** : le port pour sur lequel joindre votre appareil par défaut 5001


Etat
----

Visualisation des commandes suivantes :

- Allumer : (Retourne 0 ou 1) 

- Etat : (Retourne 0 lorsque le poele est éteind, 1 à 7 lors du démarrage, 8 poele en fonctionnement normal, 9 à 10 arrêt du poele en cours, 51 Alarme A01 : La flamme ne s'allume pas, 52 Alarme A02 : Extinction anormal du feu (manque pellets), 53 Alarme A03 : La température du réservoir à pellet dépasse le seuil de sécurité, 54 Alarme A04 : La température des fumées d'évacation a dépassé les limites de sécurité, 55 Alarme A05 : Difficulté du tirage ou obstruction du brasier, 58 Alarme A08 : Fonctionnement anormal du ventilateur fumées, 59 Alarme A09 : La sonde fumée est endomagée) 

- Fabriquant : (retourne le type de fabriquant | Information)

- Mode ECO : (Retourne 0 ou 1)

- Type : (Retourne un entier permettant d'identifier le fonctionnement de l'appareil avec ses commandes)

Action :

- ON : (démarrage du poele)

- OFF : (Arrêt du poele)

- ECO ON : (Mode ECO)

- ECO OFF : (Arrêt du mode ECO)

Températures
------------
  
- Visualisation des températures utiles
  
- Thermostat pièce 1 : Auto-régulation de la puissance en fonction du thermostat TH Pièce 1

Puissances
----------

- Information du niveau de puissance ou du gain
  
- Action sur le niveau de puissance

Ventilateurs
------------

- Informations sur la vitesse des ventilateurs
  
- Action sur le niveau des ventilateurs
  
Eau
---  

- Uniquement poele compatible ou chaudière

  
Thermostat
----------
  
- Gérer le mode chrono interne au poele
  


Le widget
=========

A vous de personnaliser votre widget en cochant les commandes que vous souhaitez utiliser


FAQ
===


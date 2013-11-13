# language: fr
@vabf
Fonctionnalité: Test de bon fonctionnement de l'application

Scénario: 10.1 - Test page
          Soit je suis sur "/testme"
          Alors je devrais voir "It works!"

Scénario: 10.2 - Test home page 200
          Soit je suis sur "/"
	  Alors le code de status de la réponse devrait être 200
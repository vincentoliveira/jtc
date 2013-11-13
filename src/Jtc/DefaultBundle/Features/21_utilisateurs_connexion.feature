# language: fr
@utilisateur @connexion
Fonctionnalité: Connexion


Scénario: 21.1 - Bouton connexion sur la homepage
    Soit je suis sur "/"
    Alors je devrais voir "Connexion"

    Soit je suis "Connexion"
    Alors je devrais être sur "/login"

Scénario: 21.2 - Connexion OK - Username
    Soit l'utilisateur "test" existe avec le mot de passe "test"

    Soit je suis sur "/login"
    Et je remplis "_username" avec "test"
    Et je remplis "_password" avec "test"
    Lorsque je presse "_submit"
    Alors je devrais voir "Bonjour test"

Scénario: 21.3 - Connexion OK - Email
    Soit l'email "test@zecolis.com" existe avec le mot de passe "test"

    Soit je suis sur "/login"
    Et je remplis "_username" avec "test@zecolis.com"
    Et je remplis "_password" avec "test"
    Lorsque je presse "_submit"
    Alors je devrais voir "Bonjour test"

Scénario: 21.4 - Connexion KO - Mot de passe
    Soit l'utilisateur "test" existe avec le mot de passe "test"

    Soit je suis sur "/login"
    Et je remplis "_username" avec "test"
    Et je remplis "_password" avec "badpassword"
    Lorsque je presse "_submit"
    Alors je ne devrais pas voir "Bonjour test"




# language: fr
@utilisateur @profil
Fonctionnalité: Profil


Scénario: 22.1 - Profil non connecté
    Soit je suis sur "/profile"
    Alors je ne devrais pas voir "Editer mon profil"

Scénario: 22.2 - Profil connecté
    Soit l'utilisateur "test" existe avec le mot de passe "test"
    Soit je suis connecté en tant que "test" et mot de passe "test"

    Soit je suis sur "/profile"
    Alors je devrais voir "Editer mon profil"
    Et je devrais voir "Nom d'utilisateur : test"
    Et je devrais voir "Adresse e-mail : test@test.zecolis.com"

Scénario: 22.3 - Edition du profil
    Soit l'utilisateur "test" existe avec le mot de passe "test"
    Soit je suis connecté en tant que "test" et mot de passe "test"

    Soit je suis sur "/profile"
    Alors je devrais voir "Editer mon profil"
    Et je devrais voir "Nom d'utilisateur : test"
    Et je devrais voir "Adresse e-mail : test@test.zecolis.com"
    
    Soit je suis "Editer mon profil"
    Et je remplis "fos_user_profile_form[username]" avec "toto"
    Et je remplis "fos_user_profile_form[email]" avec "toto@test.zecolis.com"
    Et je remplis "fos_user_profile_form[telephone]" avec "0123456789"
    Et je remplis "fos_user_profile_form[current_password]" avec "test"
    Et je presse "submit"

    Soit je suis sur "/profile"
    Et je devrais voir "Nom d'utilisateur : toto"
    Et je devrais voir "Adresse e-mail : toto@test.zecolis.com"
    Et je devrais voir "Téléphone : 0123456789"
    

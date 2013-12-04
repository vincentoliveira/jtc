# language: fr
@utilisateur @inscription
Fonctionnalité: Inscription

Scénario: 20.1 - Bouton inscription sur la homepage
    Soit je suis sur "/"
    Alors je devrais voir "Inscription"

    Soit je suis "Inscription"
    Alors je devrais être sur "/register/"

Scénario: 20.2 - Inscription OK
    Soit je supprime l'utilisateur "test20.2"
    Soit je suis sur "/register/"
    Et je remplis "fos_user_registration_form[username]" avec "test20.2"
    Et je remplis "fos_user_registration_form[email]" avec "test20.2@zecolis.com"
    Et je remplis "fos_user_registration_form[telephone]" avec "0122334455"
    Et je remplis "fos_user_registration_form[plainPassword][first]" avec "test20.2"
    Et je remplis "fos_user_registration_form[plainPassword][second]" avec "test20.2"
    Lorsque je presse "submit"
    Alors je devrais voir "Un e-mail a été envoyé à l'adresse test20.2@zecolis.com"

Scénario: 20.3 - Inscription KO - Mot de passe
    Soit je supprime l'utilisateur "test20.3"
    Soit je suis sur "/register/"
    Et je remplis "fos_user_registration_form[username]" avec "test20.3"
    Et je remplis "fos_user_registration_form[email]" avec "test20.3@zecolis.com"
    Et je remplis "fos_user_registration_form[telephone]" avec "0122334466"
    Et je remplis "fos_user_registration_form[plainPassword][first]" avec "test20.3"
    Et je remplis "fos_user_registration_form[plainPassword][second]" avec "test20.4"
    Lorsque je presse "submit"
    Alors je ne devrais pas voir "Bonjour test20.3"

Scénario: 20.4 - Inscription KO - Email format
    Soit je supprime l'utilisateur "test20.4"
    Soit je suis sur "/register/"
    Et je remplis "fos_user_registration_form[username]" avec "test20.4"
    Et je remplis "fos_user_registration_form[email]" avec "test20.4"
    Et je remplis "fos_user_registration_form[telephone]" avec "0122334477"
    Et je remplis "fos_user_registration_form[plainPassword][first]" avec "test20.4"
    Et je remplis "fos_user_registration_form[plainPassword][second]" avec "test20.4"
    Lorsque je presse "submit"
    Alors je ne devrais pas voir "Bonjour test20.4"

Scénario: 20.5 - Inscription KO - Email existant
    Soit l'email "test@zecolis.com" existe avec le mot de passe "test"

    Soit je suis sur "/register/"
    Et je remplis "fos_user_registration_form[username]" avec "test20.5"
    Et je remplis "fos_user_registration_form[email]" avec "test@zecolis.com"
    Et je remplis "fos_user_registration_form[telephone]" avec "0122334488"
    Et je remplis "fos_user_registration_form[plainPassword][first]" avec "test"
    Et je remplis "fos_user_registration_form[plainPassword][second]" avec "test"
    Lorsque je presse "submit"
    Alors je ne devrais pas voir "Bonjour test20.5"

Scénario: 20.6 - Inscription KO - Username existant
    Soit l'utilisateur "test" existe avec le mot de passe "test"

    Soit je suis sur "/register/"
    Et je remplis "fos_user_registration_form[username]" avec "test"
    Et je remplis "fos_user_registration_form[email]" avec "test20.6@zecolis.com"
    Et je remplis "fos_user_registration_form[telephone]" avec "0122334488"
    Et je remplis "fos_user_registration_form[plainPassword][first]" avec "test"
    Et je remplis "fos_user_registration_form[plainPassword][second]" avec "test"
    Lorsque je presse "submit"
    Alors je ne devrais pas voir "Bonjour test"


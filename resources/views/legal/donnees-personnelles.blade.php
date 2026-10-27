@extends('layouts.app')

@section('title', 'Données personnelles')
@section('meta_description', 'Politique de protection des données personnelles du site TedyCRPE.')

@section('content')
    <section class="legal-section">
        <div class="legal-container">
            <h1>Données personnelles</h1>

            <p>
                Tedy Florette, Coach CRPE, collecte uniquement les données nécessaires pour répondre à vos demandes et
                assurer votre formation. Il ne les vend ni ne les cède à personne. Cette page explique quelles données sont
                traitées, pourquoi, pendant combien de temps, et comment exercer vos droits, conformément au Règlement
                général sur la protection des données (RGPD) et à la loi Informatique et Libertés.
            </p>

            <h2>Responsable du traitement</h2>
            <p>
                Tedy Florette, Coach CRPE, 117, rue de Séville, 77550 Moissy-Cramayel.
                E-mail : <a href="mailto:tflorette1408@gmail.com">tflorette1408@gmail.com</a>.
            </p>

            <h2>Données collectées et utilisation</h2>
            <div class="legal-table-wrapper">
                <table class="legal-table">
                    <thead>
                        <tr>
                            <th>Situation</th>
                            <th>Données</th>
                            <th>Pourquoi</th>
                            <th>Base légale</th>
                            <th>Durée de conservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Formulaire de contact</td>
                            <td>Adresse e-mail, message</td>
                            <td>Répondre à votre demande</td>
                            <td>Votre demande (mesures précontractuelles)</td>
                            <td>3 ans après le dernier contact</td>
                        </tr>
                        <tr>
                            <td>Réservation d'un appel découverte</td>
                            <td>Nom, e-mail, téléphone, créneau choisi</td>
                            <td>Organiser l'appel</td>
                            <td>Votre demande (mesures précontractuelles)</td>
                            <td>3 ans après le dernier contact</td>
                        </tr>
                        <tr>
                            <td>Compte élève</td>
                            <td>Nom, prénom, e-mail, mot de passe chiffré, objectif (Bac+3 ou Bac+5), date d'inscription</td>
                            <td>Donner accès à l'espace de formation</td>
                            <td>Exécution du contrat de formation</td>
                            <td>Durée de la formation, puis 3 ans</td>
                        </tr>
                        <tr>
                            <td>Suivi de la formation</td>
                            <td>Progression, cours validés, devoirs envoyés, messages au tuteur</td>
                            <td>Suivre et accompagner votre préparation</td>
                            <td>Exécution du contrat de formation</td>
                            <td>Durée de la formation, puis 3 ans</td>
                        </tr>
                        <tr>
                            <td>Facturation</td>
                            <td>Nom, adresse, prestation, montant, paiement</td>
                            <td>Respecter les obligations comptables</td>
                            <td>Obligation légale</td>
                            <td>10 ans</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2>Destinataires</h2>
            <p>
                Vos données sont réservées à Tedy Florette. Elles peuvent être traitées par des prestataires techniques,
                uniquement pour faire fonctionner le service :
            </p>
            <ul>
                <li>l'hébergeur du site : OVH SAS (OVHcloud), 2, rue Kellermann, 59100 Roubaix, France ;</li>
                <li>le développeur du site, Lohan Vapaille (Lovaweb), pour la maintenance ;</li>
                <li>
                    l'outil de réservation de l'appel découverte :
                    <a href="https://calendar.app.google/pRv288tjCGrFHATr8" target="_blank" rel="noopener">Google Agenda</a>.
                </li>
            </ul>
            <p>
                Certaines ressources de la formation sont hébergées chez des services tiers : les vidéos et podcasts sur
                Google Drive, les flashcards et cartes mentales sur Claude (Anthropic). Le groupe WhatsApp (Meta) est
                facultatif : y participer rend votre numéro visible des autres membres. Ces services peuvent traiter des
                données en dehors de l'Union européenne, avec les garanties prévues par le RGPD.
            </p>

            <h2>Cookies</h2>
            <p>
                Le site utilise uniquement les cookies nécessaires à son fonctionnement, notamment pour garder votre
                session ouverte dans l'espace de formation. Ils ne demandent pas votre consentement. Aucun outil de mesure
                d'audience n'est utilisé.
            </p>

            <h2>Sécurité</h2>
            <p>
                L'accès à l'espace de formation est protégé par un identifiant et un mot de passe personnel. Ne
                communiquez pas vos identifiants.
            </p>

            <h2>Vos droits</h2>
            <p>
                Vous pouvez accéder à vos données, les faire rectifier ou effacer, vous opposer à leur traitement ou en
                limiter l'usage, et demander leur portabilité. Pour exercer ces droits, écrivez à
                <a href="mailto:tflorette1408@gmail.com">tflorette1408@gmail.com</a>. Une réponse vous sera apportée dans
                un délai d'un mois.
            </p>
            <p>
                Si vous estimez que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à la CNIL
                (<a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a>).
            </p>

            <h2>Mise à jour</h2>
            <p>
                Cette page peut être modifiée pour tenir compte de l'évolution du site. Dernière mise à jour : 27 octobre
                2026.
            </p>
        </div>
    </section>
@endsection

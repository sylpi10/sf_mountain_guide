import './stimulus_bootstrap.js';
import * as Turbo from '@hotwired/turbo';

// Turbo Drive : les liens chargent la page en fetch et ne remplacent que le <body> (effet SPA).
// Les formulaires restent en envoi classique : Turbo exigerait un statut 422 sur chaque formulaire invalide.
Turbo.config.forms.mode = 'optin';

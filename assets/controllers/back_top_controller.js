import { Controller } from '@hotwired/stimulus';

// Bouton « retour en haut », visible après un peu de défilement
export default class extends Controller {
    connect() {
        // l'icône SVG (.back-top) porte la position fixe et l'opacité
        this.icon = this.element.querySelector('.back-top');
        this.onScroll = this.onScroll.bind(this);
        window.addEventListener('scroll', this.onScroll, { passive: true });
        this.onScroll();
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    onScroll() {
        this.icon.style.opacity = window.scrollY > 180 ? '1' : '0';
    }
}

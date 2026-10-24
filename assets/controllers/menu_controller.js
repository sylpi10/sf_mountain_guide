import { Controller } from '@hotwired/stimulus';

// Menu principal : ouverture mobile, fermeture au clic sur un lien, fond au défilement (desktop)
export default class extends Controller {
    static targets = ['toggler', 'collapse'];

    connect() {
        this.onScroll = this.onScroll.bind(this);
        window.addEventListener('scroll', this.onScroll, { passive: true });
        this.onScroll();
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    toggle() {
        this.setOpen(!this.collapseTarget.classList.contains('show'));
    }

    close(event) {
        if (event.target.closest('a')) {
            this.setOpen(false);
        }
    }

    setOpen(open) {
        this.collapseTarget.classList.toggle('show', open);
        this.togglerTarget.setAttribute('aria-expanded', open);
    }

    onScroll() {
        if (!document.body.classList.contains('base-page') || window.innerWidth <= 991) {
            return;
        }
        this.element.classList.toggle('scrolled-nav', window.scrollY > 360);
    }
}

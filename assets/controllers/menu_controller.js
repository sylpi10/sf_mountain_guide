import { Controller } from '@hotwired/stimulus';

// Menu principal : ouverture mobile, fermeture au clic sur un lien, fond au défilement (desktop),
// sous-menu « Activités » (survol sur desktop, bouton flèche partout, Échap pour fermer)
export default class extends Controller {
    static targets = ['toggler', 'collapse', 'dropdown', 'dropdownToggle', 'dropdownPanel'];

    connect() {
        this.onScroll = this.onScroll.bind(this);
        this.onClickOutside = this.onClickOutside.bind(this);
        window.addEventListener('scroll', this.onScroll, { passive: true });
        document.addEventListener('click', this.onClickOutside);
        this.onScroll();
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
        document.removeEventListener('click', this.onClickOutside);
    }

    toggle() {
        this.setOpen(!this.collapseTarget.classList.contains('show'));
    }

    close(event) {
        if (event.target.closest('a')) {
            this.setOpen(false);
            this.setDropdown(false);
        }
    }

    setOpen(open) {
        this.collapseTarget.classList.toggle('show', open);
        this.togglerTarget.setAttribute('aria-expanded', open);
    }

    toggleDropdown(event) {
        event.stopPropagation();
        this.setDropdown(!this.dropdownTarget.classList.contains('open'));
    }

    // clic sur « Activités » : ouvre le sous-menu au lieu d'aller sur la page (lien « Toutes les activités » dans le panneau ;
    // le href reste pour les robots et sans JS). Sur desktop le survol l'a déjà ouvert : le clic le laisse ouvert.
    openFromLink(event) {
        if (!this.hasDropdownPanelTarget) {
            return;
        }
        event.preventDefault();
        event.stopPropagation(); // sinon menu#close referme le menu mobile (clic sur un lien)
        this.setDropdown(this.isDesktop() || !this.dropdownTarget.classList.contains('open'));
    }

    // survol : desktop uniquement (sur mobile le sous-menu s'ouvre en accordéon au clic)
    openDropdown() {
        if (this.isDesktop()) {
            this.setDropdown(true);
        }
    }

    closeDropdown(event) {
        if (event.type === 'keydown') {
            this.setDropdown(false);
            if (this.hasDropdownToggleTarget) {
                this.dropdownToggleTarget.focus();
            }
        } else if (this.isDesktop()) {
            this.setDropdown(false);
        }
    }

    setDropdown(open) {
        if (!this.hasDropdownTarget) {
            return;
        }
        this.dropdownTarget.classList.toggle('open', open);
        if (this.hasDropdownToggleTarget) {
            this.dropdownToggleTarget.setAttribute('aria-expanded', open);
        }
    }

    onClickOutside(event) {
        if (this.hasDropdownTarget && !this.dropdownTarget.contains(event.target)) {
            this.setDropdown(false);
        }
    }

    isDesktop() {
        return window.innerWidth > 991;
    }

    onScroll() {
        if (!document.body.classList.contains('base-page') || !this.isDesktop()) {
            return;
        }
        this.element.classList.toggle('scrolled-nav', window.scrollY > 360);
    }
}

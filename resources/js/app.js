import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

Alpine.plugin(focus);
Alpine.plugin(persist);

const root = document.documentElement;

function storeThemePreference(value) {
    try {
        localStorage.setItem('theme', value);
    } catch (error) {
        // Mode privat atau storage diblokir: tema tetap berganti, hanya tidak diingat.
    }
}

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

Alpine.store('theme', {
    dark: root.classList.contains('dark'),

    toggle() {
        this.apply(!this.dark);
        storeThemePreference(this.dark ? 'dark' : 'light');
    },


    apply(dark, { animate = true } = {}) {
        // State dicatat langsung; hanya tampilan (class) yang menunggu potret View Transition.
        this.dark = dark;

        const update = () => {
            root.classList.toggle('dark', dark);
            root.classList.toggle('light', !dark);
            document.querySelector('meta[name="theme-color"]')?.setAttribute('content', dark ? '#050505' : '#fafaf9');
        };

        if (!animate || !document.startViewTransition || prefersReducedMotion.matches) {
            update();

            return;
        }

        // Klik beruntun: transisi sebelumnya otomatis diselesaikan oleh browser.
        document.startViewTransition(update).ready.catch(() => {});
    },
});

// Tema ikut berganti di tab lain yang sedang membuka situs ini (event `storage` hanya terpicu di tab lain).
window.addEventListener('storage', (event) => {
    if (event.key === 'theme' && event.newValue) {
        Alpine.store('theme').apply(event.newValue !== 'light', { animate: false });
    }
});

/**
 * Menu yang dibuka lewat klik/tap/keyboard (bukan hover saja), dipakai dropdown Banten.
 * Hover tetap membuka menu untuk pengguna mouse.
 */
Alpine.data('disclosure', (hoverable = false) => ({
    open: false,
    openedByHover: false,
    hoverable,

    toggle() {
        // Klik setelah menu terbuka karena hover tidak boleh langsung menutupnya.
        this.open = this.openedByHover ? true : !this.open;
        this.openedByHover = false;
    },

    close(focusTrigger = false) {
        if (!this.open) {
            return;
        }

        this.open = false;
        this.openedByHover = false;

        if (focusTrigger) {
            this.$refs.trigger?.focus();
        }
    },

    hoverOpen() {
        if (this.hoverable && !this.open && window.matchMedia('(hover: hover)').matches) {
            this.open = true;
            this.openedByHover = true;
        }
    },

    hoverClose() {
        if (this.hoverable && window.matchMedia('(hover: hover)').matches) {
            this.open = false;
            this.openedByHover = false;
        }
    },
}));

/**
 * Tombol bagikan: salin tautan dan share bawaan HP (navigator.share).
 */
Alpine.data('share', (url, title) => ({
    copied: false,
    canNativeShare: typeof navigator.share === 'function',

    async copy() {
        try {
            await navigator.clipboard.writeText(url);
        } catch (error) {
            const field = document.createElement('textarea');
            field.value = url;
            document.body.appendChild(field);
            field.select();
            document.execCommand('copy');
            field.remove();
        }

        this.copied = true;
        setTimeout(() => (this.copied = false), 2500);
    },

    async nativeShare() {
        try {
            await navigator.share({ title, url });
        } catch (error) {
            // Pembaca menutup lembar share; tidak perlu tindakan.
        }
    },
}));

/**
 * Form Pasang Iklan: belum ada penyimpanan di server (PRD 10.3), jadi pesan
 * disusun lalu dikirim lewat WhatsApp atau email — tanpa pesan "terkirim" palsu.
 */
Alpine.data('adInquiry', (whatsapp, email) => ({
    name: '',
    company: '',
    contact: '',
    slot: '',
    message: '',
    whatsapp,
    email,

    get body() {
        return [
            'Halo tim iklan B.Today, saya ingin informasi pemasangan iklan.',
            `Nama: ${this.name}`,
            this.company ? `Perusahaan/Brand: ${this.company}` : null,
            this.contact ? `Kontak: ${this.contact}` : null,
            this.slot ? `Slot diminati: ${this.slot}` : null,
            this.message ? `Kebutuhan: ${this.message}` : null,
        ].filter(Boolean).join('\n');
    },

    get mailtoUrl() {
        return `mailto:${this.email}?subject=${encodeURIComponent('Permintaan Pasang Iklan B.Today')}&body=${encodeURIComponent(this.body)}`;
    },

    chooseSlot(label) {
        this.slot = label;
        document.getElementById('form-iklan')?.scrollIntoView({ behavior: 'smooth' });
        this.$nextTick(() => document.getElementById('iklan-nama')?.focus({ preventScroll: true }));
    },

    send() {
        const url = this.whatsapp
            ? `https://wa.me/${this.whatsapp}?text=${encodeURIComponent(this.body)}`
            : this.mailtoUrl;

        window.open(url, '_blank', 'noopener');
    },
}));

/**
 * Draf otomatis form Kirim Tulisan di localStorage, supaya tulisan panjang tidak
 * hilang karena sesi habis (419), batas kirim (429), atau koneksi putus.
 */
Alpine.data('guestDraft', (hasServerInput = false, submitted = false) => ({
    draft: Alpine.$persist({}).as('btoday-kirim-tulisan'),
    canRestore: false,
    submitting: false,
    wordCount: 0,

    init() {
        if (submitted) {
            this.draft = {};

            return;
        }

        this.canRestore = !hasServerInput && Object.values(this.draft).some((value) => value);
        this.countWords();
    },

    save(event) {
        const field = event.target;

        if (field.name && field.name !== '_token') {
            this.draft = { ...this.draft, [field.name]: field.value };
        }

        if (field.name === 'content') {
            this.countWords();
        }
    },

    restore() {
        Object.entries(this.draft).forEach(([name, value]) => {
            const field = this.$root.querySelector(`[name="${name}"]`);

            if (field) {
                field.value = value;
            }
        });
        this.canRestore = false;
        this.countWords();
    },

    discard() {
        this.draft = {};
        this.canRestore = false;
    },

    countWords() {
        const content = this.$root.querySelector('[name="content"]')?.value ?? '';
        this.wordCount = content.trim() === '' ? 0 : content.trim().split(/\s+/).length;
    },
}));

/**
 * Gambar artikel dari URL eksternal bisa hilang (404). Ganti dengan placeholder
 * bermerek dari `data-fallback`, bukan ikon gambar rusak. Lewat JS, bukan atribut
 * onerror inline, agar tetap kompatibel dengan header CSP (PRD D.8).
 */
function useImageFallback(image) {
    const fallback = image.dataset.fallback;

    if (fallback && !image.src.endsWith(fallback)) {
        image.src = fallback;
    }
}

document.addEventListener(
    'error',
    (event) => {
        if (event.target instanceof HTMLImageElement) {
            useImageFallback(event.target);
        }
    },
    true,
);

// Gambar yang sudah gagal sebelum script ini dimuat.
document.querySelectorAll('img[data-fallback]').forEach((image) => {
    if (image.complete && image.naturalWidth === 0) {
        useImageFallback(image);
    }
});

window.Alpine = Alpine;

Alpine.start();

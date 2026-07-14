import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import { createIcons, icons } from 'duo-icons';

// Theme management
const getPreferredTheme = () => {
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
        return storedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const setTheme = (theme) => {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme);
    }
    
    // Update active icon state
    updateThemeUI(theme);
};

const updateThemeUI = (theme) => {
    const themeBtn = document.querySelector('#theme-switcher');
    if (!themeBtn) return;

    if (theme === 'dark') {
        themeBtn.innerHTML = `<svg class="duo-icon" style="width:20px;height:20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`; // Sun icon
    } else {
        themeBtn.innerHTML = `<svg class="duo-icon" style="width:20px;height:20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`; // Moon icon
    }
};

// Initialize theme
const currentTheme = getPreferredTheme();
setTheme(currentTheme);

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme !== 'light' && storedTheme !== 'dark') {
        setTheme(getPreferredTheme());
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Initialize DuoIcons
    createIcons({ icons });

    // Setup theme button listener
    updateThemeUI(getPreferredTheme());
    
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('#theme-switcher');
        if (btn) {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            setTheme(next);
        }
    });

    // Mobile sidebar toggle
    document.body.addEventListener('click', (e) => {
        const sidebarToggle = e.target.closest('.sidebar-toggle');
        if (sidebarToggle) {
            const sidebar = document.querySelector('.dashboard-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }
    });
});

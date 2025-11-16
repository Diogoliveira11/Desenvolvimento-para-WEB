document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. DEFINIR VARIÁVEIS PRIMEIRO ---
    // Encontra todos os elementos principais que o script vai usar
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    const profileBtnDesktop = document.getElementById('profile-btn-desktop');
    const profileDropdownDesktop = document.getElementById('profile-dropdown-desktop');
    
    
    // --- 2. LÓGICA DO MENU MOBILE (Hamburger) ---
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', (event) => {
            event.stopPropagation(); // Impede que o clique feche o menu imediatamente
            
            // Se o menu de perfil (desktop) estiver aberto, fecha-o
            if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden')) {
                profileDropdownDesktop.classList.add('hidden');
            }
            
            // Abre/Fecha o menu mobile
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- 3. LÓGICA DO MENU DE PERFIL (Desktop) ---
    // (Esta era a que estava a falhar)
    if (profileBtnDesktop && profileDropdownDesktop) {
        profileBtnDesktop.addEventListener('click', (event) => {
            event.stopPropagation(); // Impede que o clique feche o menu imediatamente
            
            // Se o menu mobile estiver aberto, fecha-o
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
            
            // Abre/Fecha o menu de perfil
            profileDropdownDesktop.classList.toggle('hidden');
        });
    }

    // --- 4. LÓGICA DE FECHAR MENUS AO CLICAR FORA ---
    document.addEventListener('click', function(event) {
        
        // Fechar menu mobile se clicar fora dele e fora do botão
        if (mobileMenu && !mobileMenu.classList.contains('hidden') && !mobileMenu.contains(event.target) && !menuBtn.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
        
        // Fechar menu de perfil se clicar fora dele e fora do botão
        if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden') && !profileDropdownDesktop.contains(event.target) && !profileBtnDesktop.contains(event.target)) {
            profileDropdownDesktop.classList.add('hidden');
        }
    });
    
    // --- 5. LÓGICA DO BOTÃO "VOLTAR" (que corrigimos antes) ---
    const btnsVoltar = document.querySelectorAll('.js-voltar');
    btnsVoltar.forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Previne que o link 'a' faça algo
            window.history.back();
        });
    });

    // --- 6. LÓGICA DA GALERIA (do alojamento.php) ---
    const openBtn = document.getElementById('open-gallery-btn');
    const closeBtn = document.getElementById('close-gallery-btn');
    const galleryModal = document.getElementById('gallery-modal');

    if(openBtn && closeBtn && galleryModal) {
        openBtn.addEventListener('click', function() {
            galleryModal.classList.remove('hidden');
        });
        
        closeBtn.addEventListener('click', function() {
            galleryModal.classList.add('hidden');
        });
        
        galleryModal.addEventListener('click', function(event) {
            if (event.target === galleryModal) { // Fecha só se clicar no fundo
                galleryModal.classList.add('hidden');
            }
        });
    }

});
// --- FUNÇÕES GLOBAIS (Acessíveis pelo HTML 'onclick') ---

// 1. Funções do Modal de Avaliação
function abrirModalAvaliacao() {
    const modal = document.getElementById('avaliacao-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function fecharModalAvaliacao() {
    const modal = document.getElementById('avaliacao-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

   function setMinCheckoutDate(checkInDateString) {
    const checkoutInput = document.getElementById('check_out');
    
    if (checkInDateString) {
        // 1. Converte a string de Check-in para um objeto Date
        const checkInDate = new Date(checkInDateString);
        
        // 2. Adiciona um dia para definir a data mínima de Check-out
        checkInDate.setDate(checkInDate.getDate() + 1);
        
        // 3. Formata a nova data (dia seguinte) para o formato YYYY-MM-DD
        const minCheckoutDate = checkInDate.toISOString().split('T')[0];

        // 4. Aplica a nova data mínima (dia seguinte)
        checkoutInput.min = minCheckoutDate;
        
        // 5. Se o valor atual de checkout for menor que o novo mínimo, limpa o campo
        if (checkoutInput.value < minCheckoutDate) {
            checkoutInput.value = minCheckoutDate;
        }
    }
}

// 2. Função de Suporte FAQ (toggleSubquestions)
const toggleSubquestions = function(id) {
    const subquestions = document.getElementById(id);
    
    if (subquestions) {
        subquestions.classList.toggle('hidden');
    }
};


document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. DEFINIR VARIÁVEIS PRIMEIRO (DOM global) ---
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    const profileBtnDesktop = document.getElementById('profile-btn-desktop');
    const profileDropdownDesktop = document.getElementById('profile-dropdown-desktop');
    
    
    // --- 2. LÓGICA DO MENU MOBILE (Hamburger) ---
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            
            // Se o menu de perfil estiver aberto, fecha-o
            if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden')) {
                profileDropdownDesktop.classList.add('hidden');
            }
            
            // Abre/Fecha o menu mobile
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- 3. LÓGICA DO MENU DE PERFIL (Desktop) ---
    if (profileBtnDesktop && profileDropdownDesktop) {
        profileBtnDesktop.addEventListener('click', (event) => {
            event.stopPropagation();
            
            // Se o menu mobile estiver aberto, fecha-o
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
            
            // Abre/Fecha o menu de perfil
            profileDropdownDesktop.classList.toggle('hidden');
        });
    }

    // --- 4. LÓGICA DE FECHAR MENUS AO CLICAR FORA (GLOBAL) ---
    document.addEventListener('click', function(event) {
        
        // Fechar menu mobile se clicar fora
        if (mobileMenu && !mobileMenu.classList.contains('hidden') && !mobileMenu.contains(event.target) && (!menuBtn || !menuBtn.contains(event.target))) {
            mobileMenu.classList.add('hidden');
        }
        
        // Fechar menu de perfil se clicar fora
        if (profileDropdownDesktop && !profileDropdownDesktop.classList.contains('hidden') && !profileDropdownDesktop.contains(event.target) && (!profileBtnDesktop || !profileBtnDesktop.contains(event.target))) {
            profileDropdownDesktop.classList.add('hidden');
        }
    });
    
    // --- 5. LÓGICA DO BOTÃO "VOLTAR" ---
    const btnsVoltar = document.querySelectorAll('.js-voltar');
    btnsVoltar.forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            window.history.back();
        });
    });

    // --- 6. LÓGICA DA GALERIA (Modal) ---
    (function() {
        const openBtn = document.getElementById('open-gallery-btn');
        const closeBtn = document.getElementById('close-gallery-btn');
        const galleryModal = document.getElementById('gallery-modal');

        if(openBtn && closeBtn && galleryModal) {
            
            function closeModal() {
                galleryModal.classList.add('hidden');
                document.body.style.overflow = ''; // RESTAURA O SCROLL
            }
            
            openBtn.addEventListener('click', function(event) {
                event.preventDefault();
                galleryModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // BLOQUEIA O SCROLL
            });
            
            closeBtn.addEventListener('click', closeModal);
            
            galleryModal.addEventListener('click', function(event) {
                if (event.target === galleryModal) { 
                    closeModal();
                }
            });
        }
    })();

    // global.js (Código a inserir no final, DENTRO do DOMContentLoaded)

    // --- 7. LÓGICA DO CHECKOUT E MODAIS DE CONFIRMAÇÃO/AVALIAÇÃO ---
    
    const form = document.getElementById('form-pagamento');
    const modal = document.getElementById('modal-confirmacao');
    const modalContent = document.getElementById('modal-content');
    const modalFecharBtn = document.getElementById('modal-fechar-btn');
    
    // Assegura que estamos na página de checkout e que os elementos existem
    if (form && modal) {

        // O botão já é do tipo submit, mas usamos o evento do formulário para garantir que a validação HTML5 é acionada.
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verifica a validação do formulário HTML5
            if (!form.reportValidity()) {
                return;
            }

            // Mostrar o modal de loading
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const formData = new FormData(form);
            
            // Enviar dados para o script de processamento AJAX (chamando finalizarreserva.php)
            fetch('finalizarreserva.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    // Se o status HTTP não for 200, tenta ler a mensagem de erro JSON
                    return response.json().then(data => { throw new Error(data.error || 'Erro no servidor.'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.id_reserva) {
                    // Se a inserção na BD for bem-sucedida, carrega o conteúdo de confirmação
                    loadConfirmationContent(data.id_reserva);
                } else {
                    throw new Error('Resposta inválida do servidor.');
                }
            })
            .catch(error => {
                // Mostrar erro no modal
                modalContent.innerHTML = `
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-red-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">ERRO AO PROCESSAR PAGAMENTO</h2>
                        <p class="text-gray-700">${error.message}</p>
                    </div>
                `;
                modalFecharBtn.classList.remove('hidden');
                console.error('Erro de Processamento:', error);
            });
        });
    } // Fim da verificação if(form && modal)

    
    // Funções auxiliares (devem estar fora do bloco form.addEventListener, mas dentro do DOMContentLoaded)

    function loadConfirmationContent(reservaId) {
        // Carrega o template confirmacao.php para dentro do modal
        fetch(`confirmacao.php?reserva=${reservaId}`)
        .then(response => response.text())
        .then(html => {
            modalContent.innerHTML = html;
            modalFecharBtn.classList.remove('hidden');

            // Ajusta o tamanho do modal para o layout da confirmação
            const modalInner = modal.querySelector('.bg-white');
            modalInner.classList.remove('max-w-lg');
            modalInner.classList.add('max-w-xl');
        })
        .catch(error => {
            modalContent.innerHTML = `<div class="p-8 text-center text-red-600">Falha ao carregar a confirmação.</div>`;
            modalFecharBtn.classList.remove('hidden');
        });
    }

    function loadAvaliacaoModal(alojamentoId) {
        const modalAvaliacao = document.getElementById('modal-avaliacao');
        const modalAvaliacaoContent = document.getElementById('modal-avaliacao-content');
        
        modalAvaliacao.classList.remove('hidden');
        modalAvaliacao.classList.add('flex');
        
        // Carrega o conteúdo do formulário de avaliação (avaliaralojamento.php)
        fetch(`avaliaralojamento.php?id_alojamento=${alojamentoId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const content = doc.querySelector('.max-w-3xl'); 
            
            if (content) {
                modalAvaliacaoContent.innerHTML = content.innerHTML;
                
                // Adicionar listener de submissão para o novo formulário de avaliação
                const formAvaliacao = modalAvaliacaoContent.querySelector('form');
                if (formAvaliacao) {
                    formAvaliacao.addEventListener('submit', handleAvaliacaoSubmit);
                }
            } else {
                modalAvaliacaoContent.innerHTML = `<div class="p-8 text-center text-red-600">Erro: Não foi possível carregar o formulário.</div>`;
            }
        });
    }

    // Adiciona listener ao botão 'Faça a sua avaliação!' (dentro do modal de confirmação)
    document.body.addEventListener('click', function(e) {
        if (e.target.id === 'abrir-modal-avaliacao') {
            e.preventDefault();
            const alojamentoId = e.target.dataset.alojamentoId;
            
            // Esconde o modal de confirmação do checkout primeiro
            document.getElementById('modal-confirmacao').classList.add('hidden');
            
            loadAvaliacaoModal(alojamentoId);
        }
    });

    // Função para lidar com a submissão do formulário de avaliação (para evitar recarregar a página)
    function handleAvaliacaoSubmit(e) {
        e.preventDefault();
        const form = e.target;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = 'A enviar...';
        submitBtn.disabled = true;

        // O action deste form é "processaavaliacao.php"
        fetch(form.action, { 
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.text())
        .then(text => {
            // Sucesso: Substitui o formulário pela mensagem de sucesso
            const modalAvaliacaoContent = document.getElementById('modal-avaliacao-content');
            modalAvaliacaoContent.innerHTML = `
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Avaliação Enviada!</h2>
                    <p class="text-gray-600">Obrigado pela sua contribuição. Pode fechar esta janela.</p>
                </div>
            `;
            // Fecha o modal de avaliação.
            setTimeout(() => {
                document.getElementById('modal-avaliacao').classList.add('hidden');
            }, 3000); // Fecha automaticamente após 3 segundos
        })
        .catch(error => {
            alert('Erro ao enviar avaliação: ' + error);
            submitBtn.innerHTML = 'Tentar Novamente';
            submitBtn.disabled = false;
        });
    }
    
}); 
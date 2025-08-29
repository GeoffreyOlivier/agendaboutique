/**
 * Améliorations pour la mise en page de la messagerie intégrée WireChat
 */

document.addEventListener('DOMContentLoaded', function() {
    // Attendre que WireChat soit complètement initialisé
    setTimeout(initializeChatLayout, 500);
});

function initializeChatLayout() {
    console.log('Initialisation des améliorations de mise en page du chat...');
    
    // Observer les changements dans le DOM pour détecter quand le chat est chargé
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Vérifier si c'est un composant de chat WireChat
                        if (node.querySelector && node.querySelector('[class*="wirechat"]')) {
                            setTimeout(() => {
                                fixChatLayout();
                                setupAutoScroll();
                                fixFooterPosition();
                            }, 100);
                        }
                    }
                });
            }
        });
    });
    
    // Observer le body pour détecter les nouveaux composants
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Initialisation immédiate si le chat est déjà présent
    if (document.querySelector('[class*="wirechat"]')) {
        setTimeout(() => {
            fixChatLayout();
            setupAutoScroll();
            fixFooterPosition();
        }, 100);
    }
}

function fixChatLayout() {
    console.log('Correction de la mise en page du chat...');
    
    // Correction de la hauteur de la page principale
    const chatContainer = document.querySelector('.w-full.flex.min-h-full.h-full.rounded-lg');
    if (chatContainer) {
        chatContainer.style.height = '100vh';
        chatContainer.style.minHeight = '100vh';
        chatContainer.style.overflow = 'hidden';
    }
    
    // Correction de la sidebar
    const sidebar = document.querySelector('.hidden.md\\:grid.bg-inherit.border-r');
    if (sidebar) {
        sidebar.style.height = '100vh';
        sidebar.style.minHeight = '100vh';
        sidebar.style.overflowY = 'auto';
    }
    
    // Correction de la zone principale des messages
    const mainChat = document.querySelector('main.grid.w-full.grow');
    if (mainChat) {
        mainChat.style.height = '100vh';
        mainChat.style.minHeight = '100vh';
        mainChat.style.overflow = 'hidden';
        mainChat.style.display = 'flex';
        mainChat.style.flexDirection = 'column';
    }
    
    // Correction du composant de chat principal
    const chatComponent = document.querySelector('.w-full.transition');
    if (chatComponent) {
        chatComponent.style.height = '100%';
        chatComponent.style.display = 'flex';
        chatComponent.style.flexDirection = 'column';
        chatComponent.style.overflow = 'hidden';
    }
    
    // Correction de la structure interne
    const chatStructure = document.querySelector('.flex.flex-col.grow.h-full.relative');
    if (chatStructure) {
        chatStructure.style.height = '100%';
        chatStructure.style.display = 'flex';
        chatStructure.style.flexDirection = 'column';
    }
    
    // Correction du body des messages
    const messageBody = document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4');
    if (messageBody) {
        messageBody.style.flex = '1';
        messageBody.style.overflowY = 'auto';
        messageBody.style.height = 'auto';
        messageBody.style.minHeight = '0';
        messageBody.style.maxHeight = 'calc(100vh - 200px)';
        messageBody.style.padding = '1rem';
    }
    
    // Correction du footer
    const footer = document.querySelector('.shrink-0.h-auto.relative.sticky.bottom-0.mt-auto');
    if (footer) {
        footer.style.position = 'sticky';
        footer.style.bottom = '0';
        footer.style.zIndex = '100';
        footer.style.borderTop = '1px solid var(--wc-light-border)';
        footer.style.marginTop = 'auto';
        footer.style.width = '100%';
        footer.style.background = 'var(--wc-light-secondary)';
        
        // Vérifier si on est en mode sombre
        if (document.documentElement.classList.contains('dark')) {
            footer.style.background = 'var(--wc-dark-secondary)';
            footer.style.borderTop = '1px solid var(--wc-dark-border)';
        }
    }
    
    // Correction de la zone de saisie
    const inputArea = document.querySelector('.px-3.border-t.shadow-sm');
    if (inputArea) {
        inputArea.style.background = 'var(--wc-light-secondary)';
        inputArea.style.borderTop = '1px solid var(--wc-light-border)';
        
        if (document.documentElement.classList.contains('dark')) {
            inputArea.style.background = 'var(--wc-dark-secondary)';
            inputArea.style.borderTop = '1px solid var(--wc-dark-border)';
        }
    }
}

function setupAutoScroll() {
    console.log('Configuration du scroll automatique...');
    
    const messageBody = document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4');
    if (messageBody) {
        // Scroll automatique vers le bas lors de l'ajout de nouveaux messages
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Attendre que le DOM soit mis à jour
                    setTimeout(() => {
                        scrollToBottom(messageBody);
                    }, 50);
                }
            });
        });
        
        observer.observe(messageBody, {
            childList: true,
            subtree: true
        });
        
        // Scroll initial vers le bas
        setTimeout(() => {
            scrollToBottom(messageBody);
        }, 200);
    }
}

function scrollToBottom(element) {
    if (element) {
        element.scrollTop = element.scrollHeight;
    }
}

function fixFooterPosition() {
    console.log('Correction de la position du footer...');
    
    const footer = document.querySelector('.shrink-0.h-auto.relative.sticky.bottom-0.mt-auto');
    if (footer) {
        // Vérifier la hauteur du contenu des messages
        const messageBody = document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4');
        if (messageBody) {
            const messageHeight = messageBody.scrollHeight;
            const containerHeight = messageBody.clientHeight;
            
            // Si le contenu est plus petit que le conteneur, forcer le footer en bas
            if (messageHeight < containerHeight) {
                footer.style.position = 'absolute';
                footer.style.bottom = '0';
            } else {
                footer.style.position = 'sticky';
                footer.style.bottom = '0';
            }
        }
    }
}

// Fonction pour forcer la mise à jour de la mise en page
function forceLayoutUpdate() {
    setTimeout(() => {
        fixChatLayout();
        fixFooterPosition();
    }, 100);
}

// Écouter les changements de taille de fenêtre
window.addEventListener('resize', function() {
    setTimeout(() => {
        fixChatLayout();
        fixFooterPosition();
    }, 100);
});

// Écouter les changements de thème
const themeObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            setTimeout(() => {
                fixChatLayout();
                fixFooterPosition();
            }, 100);
        }
    });
});

// Observer les changements de classe sur l'élément html
if (document.documentElement) {
    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
}

// Exposer les fonctions globalement pour le débogage
window.chatLayoutHelpers = {
    fixChatLayout,
    setupAutoScroll,
    fixFooterPosition,
    forceLayoutUpdate
};


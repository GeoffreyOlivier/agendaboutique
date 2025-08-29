/**
 * Script de test pour vérifier les corrections de la messagerie
 */

// Fonction de test pour vérifier la mise en page
function testChatLayout() {
    console.log('🧪 Test de la mise en page du chat...');
    
    // Vérifier que les éléments clés existent
    const elements = {
        'Container principal': document.querySelector('.w-full.flex.min-h-full.h-full.rounded-lg'),
        'Sidebar': document.querySelector('.hidden.md\\:grid.bg-inherit.border-r'),
        'Zone principale': document.querySelector('main.grid.w-full.grow'),
        'Composant chat': document.querySelector('.w-full.transition'),
        'Structure interne': document.querySelector('.flex.flex-col.grow.h-full.relative'),
        'Body messages': document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4'),
        'Footer': document.querySelector('.shrink-0.h-auto.relative.sticky.bottom-0.mt-auto')
    };
    
    let allElementsPresent = true;
    
    Object.entries(elements).forEach(([name, element]) => {
        if (element) {
            console.log(`✅ ${name}: Présent`);
        } else {
            console.log(`❌ ${name}: Manquant`);
            allElementsPresent = false;
        }
    });
    
    if (allElementsPresent) {
        console.log('🎉 Tous les éléments sont présents !');
        testLayoutProperties();
    } else {
        console.log('⚠️ Certains éléments sont manquants');
    }
}

// Test des propriétés de mise en page
function testLayoutProperties() {
    console.log('🔍 Test des propriétés de mise en page...');
    
    const container = document.querySelector('.w-full.flex.min-h-full.h-full.rounded-lg');
    if (container) {
        const styles = window.getComputedStyle(container);
        console.log('Container principal:');
        console.log(`  - Hauteur: ${styles.height}`);
        console.log(`  - Min-height: ${styles.minHeight}`);
        console.log(`  - Overflow: ${styles.overflow}`);
    }
    
    const footer = document.querySelector('.shrink-0.h-auto.relative.sticky.bottom-0.mt-auto');
    if (footer) {
        const styles = window.getComputedStyle(footer);
        console.log('Footer:');
        console.log(`  - Position: ${styles.position}`);
        console.log(`  - Bottom: ${styles.bottom}`);
        console.log(`  - Z-index: ${styles.zIndex}`);
    }
    
    const messageBody = document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4');
    if (messageBody) {
        const styles = window.getComputedStyle(messageBody);
        console.log('Zone des messages:');
        console.log(`  - Flex: ${styles.flex}`);
        console.log(`  - Overflow-y: ${styles.overflowY}`);
        console.log(`  - Max-height: ${styles.maxHeight}`);
    }
}

// Test du scroll automatique
function testAutoScroll() {
    console.log('📜 Test du scroll automatique...');
    
    const messageBody = document.querySelector('.flex.flex-col.h-full.relative.gap-2.gap-y-4.p-4');
    if (messageBody) {
        const scrollHeight = messageBody.scrollHeight;
        const clientHeight = messageBody.clientHeight;
        const scrollTop = messageBody.scrollTop;
        
        console.log(`Scroll info:`);
        console.log(`  - Scroll height: ${scrollHeight}px`);
        console.log(`  - Client height: ${clientHeight}px`);
        console.log(`  - Scroll top: ${scrollTop}px`);
        
        if (scrollHeight > clientHeight) {
            console.log('✅ Contenu scrollable détecté');
        } else {
            console.log('ℹ️ Contenu non scrollable (peu de messages)');
        }
    }
}

// Test de la responsivité
function testResponsiveness() {
    console.log('📱 Test de la responsivité...');
    
    const width = window.innerWidth;
    console.log(`Largeur de la fenêtre: ${width}px`);
    
    if (width <= 768) {
        console.log('📱 Mode mobile détecté');
    } else if (width <= 1024) {
        console.log('💻 Mode tablette détecté');
    } else {
        console.log('🖥️ Mode desktop détecté');
    }
    
    // Vérifier les media queries
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    console.log(`Media query (max-width: 768px): ${mediaQuery.matches ? 'Active' : 'Inactive'}`);
}

// Test du thème
function testTheme() {
    console.log('🎨 Test du thème...');
    
    const isDark = document.documentElement.classList.contains('dark');
    console.log(`Thème actuel: ${isDark ? 'Sombre' : 'Clair'}`);
    
    // Vérifier les variables CSS
    const root = document.documentElement;
    const computedStyle = getComputedStyle(root);
    
    const variables = [
        '--wc-light-primary',
        '--wc-light-secondary', 
        '--wc-light-border',
        '--wc-dark-primary',
        '--wc-dark-secondary',
        '--wc-dark-border'
    ];
    
    console.log('Variables CSS:');
    variables.forEach(varName => {
        const value = computedStyle.getPropertyValue(varName);
        console.log(`  - ${varName}: ${value}`);
    });
}

// Fonction de test complète
function runAllTests() {
    console.log('🚀 Démarrage des tests de la messagerie...');
    console.log('=====================================');
    
    // Attendre que la page soit complètement chargée
    setTimeout(() => {
        testChatLayout();
        console.log('---');
        testAutoScroll();
        console.log('---');
        testResponsiveness();
        console.log('---');
        testTheme();
        console.log('---');
        console.log('✨ Tests terminés !');
    }, 1000);
}

// Exposer les fonctions de test globalement
window.chatTests = {
    testChatLayout,
    testAutoScroll,
    testResponsiveness,
    testTheme,
    runAllTests
};

// Lancer les tests automatiquement si on est sur une page de chat
if (window.location.pathname.includes('/chats/')) {
    console.log('📍 Page de chat détectée, lancement automatique des tests...');
    runAllTests();
}

// Lancer les tests manuellement
console.log('💡 Pour lancer les tests manuellement, utilisez: window.chatTests.runAllTests()');


#!/bin/bash

# Script de test pour agendaBoutique
# Usage: ./tests/run-tests.sh [option]

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction d'affichage
print_header() {
    echo -e "\n${BLUE}================================${NC}"
    echo -e "${BLUE}  $1${NC}"
    echo -e "${BLUE}================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "craftsman" ]; then
    print_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

# Nettoyer le cache avant les tests
print_info "Nettoyage du cache..."
php craftsman config:clear
php craftsman route:clear
php craftsman cache:clear

# Fonction pour exécuter les tests
run_tests() {
    local test_suite=$1
    local description=$2
    
    print_header "Exécution des tests: $description"
    
    if [ -z "$test_suite" ]; then
        php craftsman test
    else
        php craftsman test --testsuite="$test_suite"
    fi
    
    local exit_code=$?
    
    if [ $exit_code -eq 0 ]; then
        print_success "Tests $description terminés avec succès"
    else
        print_error "Tests $description ont échoué (code: $exit_code)"
    fi
    
    return $exit_code
}

# Fonction pour exécuter les tests avec couverture
run_tests_with_coverage() {
    print_header "Exécution des tests avec couverture de code"
    
    # Vérifier si Xdebug est installé
    if ! php -m | grep -q "xdebug"; then
        print_warning "Xdebug n'est pas installé. La couverture de code ne sera pas disponible."
        print_info "Installez Xdebug pour activer la couverture de code."
        return 1
    fi
    
    php craftsman test --coverage --coverage-html=coverage --coverage-text=coverage.txt
    
    local exit_code=$?
    
    if [ $exit_code -eq 0 ]; then
        print_success "Tests avec couverture terminés avec succès"
        print_info "Rapport de couverture disponible dans le dossier 'coverage/'"
        print_info "Résumé de couverture dans 'coverage.txt'"
    else
        print_error "Tests avec couverture ont échoué (code: $exit_code)"
    fi
    
    return $exit_code
}

# Fonction pour exécuter les tests en mode watch
run_tests_watch() {
    print_header "Exécution des tests en mode watch"
    print_info "Appuyez sur Ctrl+C pour arrêter"
    
    # Vérifier si Pest est installé
    if command -v ./vendor/bin/pest &> /dev/null; then
        ./vendor/bin/pest --watch
    else
        print_warning "Pest n'est pas installé. Utilisation de PHPUnit en mode normal."
        php craftsman test
    fi
}

# Menu principal
case "${1:-all}" in
    "all")
        run_tests "" "complets"
        ;;
    "unit")
        run_tests "Unit" "unitaires"
        ;;
    "feature")
        run_tests "Feature" "d'intégration"
        ;;
    "repositories")
        run_tests "Repositories" "des repositories"
        ;;
    "services")
        run_tests "Services" "des services"
        ;;
    "controllers")
        run_tests "Controllers" "des contrôleurs"
        ;;
    "middleware")
        run_tests "Middleware" "des middlewares"
        ;;
    "requests")
        run_tests "Requests" "des Form Requests"
        ;;
    "coverage")
        run_tests_with_coverage
        ;;
    "watch")
        run_tests_watch
        ;;
    "help"|"-h"|"--help")
        print_header "Aide - Script de test agendaBoutique"
        echo "Usage: ./tests/run-tests.sh [option]"
        echo ""
        echo "Options disponibles:"
        echo "  all         - Exécuter tous les tests (défaut)"
        echo "  unit        - Tests unitaires uniquement"
        echo "  feature     - Tests d'intégration uniquement"
        echo "  repositories- Tests des repositories uniquement"
        echo "  services    - Tests des services uniquement"
        echo "  controllers - Tests des contrôleurs uniquement"
        echo "  middleware  - Tests des middlewares uniquement"
        echo "  requests    - Tests des Form Requests uniquement"
        echo "  coverage    - Tests avec couverture de code"
        echo "  watch       - Tests en mode watch (avec Pest)"
        echo "  help        - Afficher cette aide"
        echo ""
        echo "Exemples:"
        echo "  ./tests/run-tests.sh"
        echo "  ./tests/run-tests.sh unit"
        echo "  ./tests/run-tests.sh coverage"
        ;;
    *)
        print_error "Option inconnue: $1"
        print_info "Utilisez './tests/run-tests.sh help' pour voir les options disponibles"
        exit 1
        ;;
esac

# Afficher le résumé
if [ $? -eq 0 ]; then
    print_success "Tous les tests sont passés avec succès ! 🎉"
else
    print_error "Certains tests ont échoué. Vérifiez les erreurs ci-dessus. 🔍"
fi

echo ""

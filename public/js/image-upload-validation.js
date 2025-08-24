/**
 * Validation côté client pour les uploads d'images
 */
class ImageUploadValidator {
    constructor() {
        this.maxFileSize = 2 * 1024 * 1024; // 2MB
        this.allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        this.init();
    }

    init() {
        // Écouter tous les inputs de type file
        document.addEventListener('change', (e) => {
            if (e.target.type === 'file') {
                this.validateFiles(e.target);
            }
        });

        // Écouter les soumissions de formulaire
        document.addEventListener('submit', (e) => {
            if (e.target.querySelector('input[type="file"]')) {
                if (!this.validateForm(e.target)) {
                    e.preventDefault();
                }
            }
        });
    }

    /**
     * Valider les fichiers sélectionnés
     */
    validateFiles(input) {
        const files = Array.from(input.files);
        const errors = [];
        const warnings = [];
        let pendingChecks = 0;

        files.forEach((file, index) => {
            // Vérifier la taille
            if (file.size > this.maxFileSize) {
                errors.push(`"${file.name}" est trop lourd (${this.formatFileSize(file.size)}). Taille maximale : 2 MB`);
            }

            // Vérifier le type
            if (!this.allowedTypes.includes(file.type)) {
                errors.push(`"${file.name}" n'est pas dans un format accepté. Formats acceptés : JPEG, PNG, JPG, GIF, WebP`);
            }

            // Vérifier les dimensions (si c'est une image)
            if (this.allowedTypes.includes(file.type)) {
                pendingChecks++;
                this.checkImageDimensions(file, index, input, warnings, () => {
                    pendingChecks--;
                    if (pendingChecks === 0) {
                        // Tous les contrôles de dimensions sont terminés
                        this.displayValidationResults(input, errors, warnings);
                    }
                });
            }
        });

        // Si pas d'images à vérifier, afficher immédiatement
        if (pendingChecks === 0) {
            this.displayValidationResults(input, errors, warnings);
        }
    }

    /**
     * Vérifier les dimensions d'une image
     */
    checkImageDimensions(file, index, input, warnings, callback) {
        const img = new Image();
        img.onload = () => {
            const width = img.width;
            const height = img.height;

            if (width < 800 || height < 600) {
                warnings.push(`"${file.name}" est de petite taille (${width} x ${height} pixels). Recommandé : au moins 800 x 600 pixels`);
            }

            if (width > 3000 || height > 3000) {
                warnings.push(`"${file.name}" est très grande (${width} x ${height} pixels). Cela peut ralentir le chargement`);
            }

            // Appeler le callback pour indiquer que la vérification est terminée
            if (callback) callback();
        };
        img.src = URL.createObjectURL(file);
    }

    /**
     * Valider un formulaire avant soumission
     */
    validateForm(form) {
        const fileInputs = form.querySelectorAll('input[type="file"]');
        let isValid = true;

        fileInputs.forEach(input => {
            if (input.files.length > 0) {
                const files = Array.from(input.files);
                const hasErrors = files.some(file => 
                    file.size > this.maxFileSize || 
                    !this.allowedTypes.includes(file.type)
                );

                if (hasErrors) {
                    isValid = false;
                    this.showError(input, 'Veuillez corriger les erreurs avant de soumettre le formulaire');
                }
            }
        });

        return isValid;
    }

    /**
     * Afficher les résultats de validation
     */
    displayValidationResults(input, errors, warnings) {
        // Supprimer les anciens messages
        this.removeValidationMessages(input);

        // Afficher les erreurs
        if (errors.length > 0) {
            this.showError(input, errors.join('<br>'));
        }

        // Afficher les avertissements
        if (warnings.length > 0) {
            this.showWarning(input, warnings.join('<br>'));
        }

        // Afficher un message de succès si tout va bien
        if (errors.length === 0 && warnings.length === 0) {
            this.showSuccess(input, 'Images valides !');
        }
    }

    /**
     * Afficher une erreur
     */
    showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded p-2';
        errorDiv.innerHTML = `<strong>Erreur :</strong> ${message}`;
        input.parentNode.appendChild(errorDiv);
    }

    /**
     * Afficher un avertissement
     */
    showWarning(input, message) {
        const warningDiv = document.createElement('div');
        warningDiv.className = 'mt-2 text-sm text-yellow-600 bg-yellow-50 border border-yellow-200 rounded p-2';
        warningDiv.innerHTML = `<strong>Avertissement :</strong> ${message}`;
        input.parentNode.appendChild(warningDiv);
    }

    /**
     * Afficher un message de succès
     */
    showSuccess(input, message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'mt-2 text-sm text-green-600 bg-green-50 border border-green-200 rounded p-2';
        successDiv.innerHTML = `<strong>✓</strong> ${message}`;
        input.parentNode.appendChild(successDiv);
    }

    /**
     * Supprimer les anciens messages de validation
     */
    removeValidationMessages(input) {
        const messages = input.parentNode.querySelectorAll('.mt-2.text-sm');
        messages.forEach(msg => {
            if (msg.textContent.includes('Erreur') || 
                msg.textContent.includes('Avertissement') || 
                msg.textContent.includes('✓')) {
                msg.remove();
            }
        });
    }

    /**
     * Formater la taille d'un fichier
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

// Initialiser la validation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    new ImageUploadValidator();
});

<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-content">
        <div class="cookie-consent-text">
            <h5 class="mb-2">
                <i class="fas fa-cookie-bite me-2"></i>Folosim Cookie-uri
            </h5>
            <p class="mb-0">
                Folosim cookie-uri pentru a îmbunătăți experiența ta pe site-ul nostru, pentru a personaliza conținutul și pentru a analiza traficul. 
                Prin continuarea navigării, accepți utilizarea cookie-urilor conform 
                <a href="<?php echo e(route('privacy')); ?>" class="text-white text-decoration-underline">Politicii de Confidențialitate</a>.
            </p>
        </div>
        <div class="cookie-consent-buttons">
            <button type="button" class="btn btn-light btn-sm me-2" id="cookieSettings">
                <i class="fas fa-cog me-1"></i>Setări
            </button>
            <button type="button" class="btn btn-outline-light btn-sm me-2" id="cookieReject">
                <i class="fas fa-times me-1"></i>Respinge
            </button>
            <button type="button" class="btn btn-warning btn-sm" id="cookieAccept">
                <i class="fas fa-check me-1"></i>Acceptă Toate
            </button>
        </div>
    </div>
</div>

<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #2C1810 0%, #1a0f0a 100%);
    color: white;
    padding: 20px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
    z-index: 9999;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.cookie-consent-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.cookie-consent-text {
    flex: 1;
}

.cookie-consent-text h5 {
    color: var(--primary-color, #D4AF37);
    font-weight: 600;
    margin-bottom: 8px;
}

.cookie-consent-text p {
    font-size: 0.9rem;
    line-height: 1.5;
}

.cookie-consent-buttons {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

.cookie-consent-buttons .btn {
    white-space: nowrap;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .cookie-consent-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .cookie-consent-buttons {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .cookie-consent-buttons .btn {
        flex: 1;
        min-width: 100px;
    }
}

/* Animation for hiding */
.cookie-consent-banner.hiding {
    animation: slideDown 0.5s ease-out forwards;
}

@keyframes slideDown {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('cookieConsentBanner');
    const acceptBtn = document.getElementById('cookieAccept');
    const rejectBtn = document.getElementById('cookieReject');
    const settingsBtn = document.getElementById('cookieSettings');

    // Check if user has already made a choice
    const cookieConsent = localStorage.getItem('cookieConsent');
    
    if (!cookieConsent) {
        // Show banner after 1 second delay for better UX
        setTimeout(function() {
            banner.style.display = 'block';
        }, 1000);
    } else {
        // Apply saved preferences
        applyCookieConsent(JSON.parse(cookieConsent));
    }

    // Accept all cookies
    acceptBtn.addEventListener('click', function() {
        const preferences = {
            essential: true,
            functional: true,
            analytics: true,
            marketing: true,
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('cookieConsent', JSON.stringify(preferences));
        localStorage.setItem('cookiePreferences', JSON.stringify(preferences));
        
        hideBanner();
        applyCookieConsent(preferences);
    });

    // Reject optional cookies
    rejectBtn.addEventListener('click', function() {
        const preferences = {
            essential: true,
            functional: false,
            analytics: false,
            marketing: false,
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('cookieConsent', JSON.stringify(preferences));
        localStorage.setItem('cookiePreferences', JSON.stringify(preferences));
        
        hideBanner();
        applyCookieConsent(preferences);
    });

    // Go to settings page
    settingsBtn.addEventListener('click', function() {
        window.location.href = "<?php echo e(route('cookies')); ?>";
    });

    function hideBanner() {
        banner.classList.add('hiding');
        setTimeout(function() {
            banner.style.display = 'none';
            banner.classList.remove('hiding');
        }, 500);
    }

    function applyCookieConsent(preferences) {
        // Apply functional cookies
        if (!preferences.functional) {
            // Remove functional cookies
            localStorage.removeItem('view_mode');
            localStorage.removeItem('locale');
            localStorage.removeItem('theme');
        }

        // Apply analytics cookies
        if (preferences.analytics) {
            // Enable Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted'
                });
            }
        } else {
            // Disable Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'denied'
                });
            }
            // Remove GA cookies
            document.cookie.split(";").forEach(function(c) {
                if (c.trim().startsWith('_ga')) {
                    document.cookie = c.trim().split("=")[0] + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            });
        }

        // Apply marketing cookies
        if (preferences.marketing) {
            // Enable marketing pixels
            console.log('Marketing cookies enabled');
        } else {
            // Disable marketing pixels
            console.log('Marketing cookies disabled');
            // Remove Facebook Pixel cookies
            document.cookie.split(";").forEach(function(c) {
                if (c.trim().startsWith('_fb') || c.trim().startsWith('fr')) {
                    document.cookie = c.trim().split("=")[0] + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            });
        }

        console.log('Cookie consent applied:', preferences);
    }
});
</script>
<?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/components/cookie-consent.blade.php ENDPATH**/ ?>
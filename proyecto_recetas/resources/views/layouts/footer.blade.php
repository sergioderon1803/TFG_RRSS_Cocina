<div class="footer-wrapper">
    <button class="footer-toggle" id="footerToggle">
        <i class="bi bi-info-circle"></i>
    </button>

    <footer class="site-footer" id="expandableFooter">
        <div class="footer-content">
            <nav class="footer-links">
                <a href="{{ route('terminos') }}" class="footer-link">
                    <i class="bi bi-file-text me-1"></i>Términos
                </a>
                <a href="{{ route('privacidad') }}" class="footer-link">
                    <i class="bi bi-shield-check me-1"></i>Privacidad
                </a>
                <a href="{{ route('cookies') }}" class="footer-link">
                    <i class="bi bi-shield-lock me-1"></i>Cookies
                </a>
                <a href="{{ route('accesibilidad') }}" class="footer-link">
                    <i class="bi bi-eye me-1"></i>Accesibilidad
                </a>
                <a href="{{ route('contacto') }}" class="footer-link">
                    <i class="bi bi-envelope me-1"></i>Contacto
                </a>
            </nav>
            <div class="footer-info">
                <small><i class="bi bi-c-circle me-1"></i>{{ date('Y') }} WeCook. Todos los derechos reservados</small>
            </div>
        </div>
    </footer>
</div>

<script>
document.getElementById('footerToggle').addEventListener('click', function() {
    document.getElementById('expandableFooter').classList.toggle('expanded');
    this.classList.toggle('active');
});
</script>

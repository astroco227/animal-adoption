/* =========================================
   Paws & Hearts — main.js
   Hamburger menu, FAQ accordion, scroll reveal, counter animation
   ========================================= */

// ── Hamburger Menu ──────────────────────────────────────────────
const hamburger = document.getElementById('hamburger-btn');
const navLinks  = document.querySelector('.nav-links');

if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('open');
        hamburger.classList.toggle('active', isOpen);
        hamburger.setAttribute('aria-expanded', isOpen);
    });

    // Close when any link is clicked
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('open');
            hamburger.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        });
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
            navLinks.classList.remove('open');
            hamburger.classList.remove('active');
        }
    });
}

// ── FAQ Accordion ───────────────────────────────────────────────
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const answer = button.nextElementSibling;

        // Close all other open items
        document.querySelectorAll('.faq-question.active').forEach(activeBtn => {
            if (activeBtn !== button) {
                activeBtn.classList.remove('active');
                activeBtn.nextElementSibling.style.maxHeight = '0';
                activeBtn.querySelector('i').style.transform = 'rotate(0deg)';
            }
        });

        button.classList.toggle('active');
        if (button.classList.contains('active')) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
            button.querySelector('i').style.transform = 'rotate(180deg)';
        } else {
            answer.style.maxHeight = '0';
            button.querySelector('i').style.transform = 'rotate(0deg)';
        }
    });
});

// ── Scroll-Reveal Animation ─────────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            // Stagger siblings slightly
            const siblings = entry.target.parentElement.querySelectorAll('.reveal-on-scroll');
            let delay = 0;
            siblings.forEach((el, idx) => { if (el === entry.target) delay = idx * 80; });
            setTimeout(() => entry.target.classList.add('revealed'), delay);
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.12 });

document.querySelectorAll(
    '.animal-card, .stat-box, .team-card, .testimonial-card, .how-step, .donate-card'
).forEach(el => {
    el.classList.add('reveal-on-scroll');
    revealObserver.observe(el);
});

// ── Animated Counter ────────────────────────────────────────────
function animateCounter(el, target, suffix) {
    let current = 0;
    const duration  = 1200; // ms
    const steps     = 50;
    const increment = Math.ceil(target / steps);
    const interval  = Math.floor(duration / steps);

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = current.toLocaleString() + suffix;
    }, interval);
}

const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const el     = entry.target;
            const target = parseInt(el.dataset.count, 10);
            const suffix = el.dataset.suffix || '';
            if (!isNaN(target)) animateCounter(el, target, suffix);
            counterObserver.unobserve(el);
        }
    });
}, { threshold: 0.3 });

document.querySelectorAll('[data-count]').forEach(el => counterObserver.observe(el));

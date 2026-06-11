// --- Client-side interactions for the public site.
// This script handles navigation, section highlighting, event carousel behavior,
// gallery lightbox interactions, contact form validation, and reveal animations.
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const navMenu = document.getElementById('navMenu');
    const hamburger = document.getElementById('hamburger');
    const scrollToTop = document.getElementById('scrollToTop');
    const eventCarousel = document.getElementById('eventsCarousel');
    const eventPrev = document.getElementById('eventPrev');
    const eventNext = document.getElementById('eventNext');
    const eventIndicators = [...document.querySelectorAll('#eventIndicators .indicator')];
    const navLinks = [...document.querySelectorAll('.nav-link')];
    const sections = navLinks
        .map((link) => {
            const href = link.getAttribute('href');
            return href && href.startsWith('#') ? document.querySelector(href) : null;
        })
        .filter(Boolean);
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let scrollTicking = false;
    let currentEventIndex = 0;

    updateFooterYear();
    initMobileNavigation();
    initScrollEffects();
    initEventCarousel();
    initGallery();
    initContactForm();
    initEventActions();
    initReveals();
    initCounters();

    // --- Update the footer's copyright year on each page load. ---
    function updateFooterYear() {
        const footerLine = document.querySelector('.footer-bottom p');
        if (!footerLine) {
            return;
        }

        const year = new Date().getFullYear();
        footerLine.innerHTML = `&copy; ${year} KUET Rover Scout Group. All rights reserved.`;
    }

    // --- Toggle the mobile menu and close it on navigation or resize. ---
    function initMobileNavigation() {
        if (!navMenu || !hamburger) {
            return;
        }

        const closeMenu = () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
        };

        const openMenu = () => {
            navMenu.classList.add('active');
            hamburger.classList.add('active');
            hamburger.setAttribute('aria-expanded', 'true');
            document.body.classList.add('menu-open');
        };

        hamburger.addEventListener('click', () => {
            if (navMenu.classList.contains('active')) {
                closeMenu();
                return;
            }

            openMenu();
        });

        navLinks.forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 480) {
                closeMenu();
            }
        });
    }

    // --- Sync the navbar state and active section link while the user scrolls. ---
    function initScrollEffects() {
        if (!navbar || !scrollToTop) {
            return;
        }

        const handleScroll = () => {
            const isScrolled = window.scrollY > 20;
            navbar.classList.toggle('scrolled', isScrolled);
            scrollToTop.classList.toggle('show', window.scrollY > 500);
            updateActiveNavLink();
        };

        window.addEventListener('scroll', () => {
            if (scrollTicking) {
                return;
            }

            scrollTicking = true;
            window.requestAnimationFrame(() => {
                handleScroll();
                scrollTicking = false;
            });
        });

        scrollToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
        });

        handleScroll();
    }

    function updateActiveNavLink() {
        if (!sections.length) {
            return;
        }

        const scrollPosition = window.scrollY + 180;
        let activeSectionId = sections[0].id;

        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                activeSectionId = section.id;
            }
        });

        navLinks.forEach((link) => {
            const href = link.getAttribute('href') || '';
            const targetId = href.startsWith('#') ? href.slice(1) : '';
            link.classList.toggle('active', targetId === activeSectionId);
        });
    }

    // --- Rotate featured events through the homepage carousel controls. ---
    function initEventCarousel() {
        if (!eventCarousel) {
            return;
        }

        const getVisibleCards = () => [...eventCarousel.querySelectorAll('.event-card:not(.is-hidden)')];
        const getVisibleIndicators = () => [...document.querySelectorAll('#eventIndicators .indicator')];

        const updateIndicators = (index) => {
            const visibleIndicators = getVisibleIndicators();
            visibleIndicators.forEach((indicator, indicatorIndex) => {
                indicator.classList.toggle('active', indicatorIndex === index);
            });
        };

        const scrollToIndex = (index) => {
            const cards = getVisibleCards();
            if (!cards.length) {
                return;
            }

            const boundedIndex = Math.max(0, Math.min(index, cards.length - 1));
            currentEventIndex = boundedIndex;
            eventCarousel.scrollTo({
                left: cards[boundedIndex].offsetLeft - eventCarousel.offsetLeft,
                behavior: reduceMotion ? 'auto' : 'smooth'
            });
            updateIndicators(boundedIndex);
        };

        const syncIndexFromScroll = () => {
            const cards = getVisibleCards();
            if (!cards.length) {
                return;
            }

            const containerCenter = eventCarousel.scrollLeft + eventCarousel.clientWidth / 2;
            let closestIndex = 0;
            let closestDistance = Number.POSITIVE_INFINITY;

            cards.forEach((card, index) => {
                const cardCenter = card.offsetLeft + card.offsetWidth / 2;
                const distance = Math.abs(cardCenter - containerCenter);

                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }
            });

            if (closestIndex !== currentEventIndex) {
                currentEventIndex = closestIndex;
                updateIndicators(closestIndex);
            }
        };

        if (eventPrev) {
            eventPrev.addEventListener('click', () => {
                scrollToIndex(currentEventIndex - 1);
            });
        }

        if (eventNext) {
            eventNext.addEventListener('click', () => {
                scrollToIndex(currentEventIndex + 1);
            });
        }

        eventIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                scrollToIndex(index);
            });
        });

        let carouselTicking = false;
        eventCarousel.addEventListener('scroll', () => {
            if (carouselTicking) {
                return;
            }

            carouselTicking = true;
            window.requestAnimationFrame(() => {
                syncIndexFromScroll();
                carouselTicking = false;
            });
        });

        window.addEventListener('resize', () => {
            scrollToIndex(currentEventIndex);
        });

        scrollToIndex(0);
    }

    // --- Support category filters and the gallery lightbox preview. ---
    function initGallery() {
        const filterButtons = [...document.querySelectorAll('.filter-btn')];
        const galleryItems = [...document.querySelectorAll('.gallery-item')];
        const modalRoot = document.getElementById('galleryLightbox');
        const modalImage = document.getElementById('lightboxImage');
        const modalTitle = document.getElementById('lightboxTitle');
        const modalMeta = document.getElementById('lightboxMeta');
        const modalCloseButton = modalRoot?.querySelector('.lightbox-close');
        const galleryMoreButton = document.getElementById('galleryMoreButton');

        if (!galleryItems.length || !modalRoot || !modalImage || !modalTitle || !modalMeta || !modalCloseButton) {
            return;
        }

        const galleryPreviewLimit = 8;
        let lastFocusedElement = null;
        let activeFilter = 'all';
        let isExpanded = false;

        const updateGalleryView = () => {
            const filteredItems = galleryItems.filter((item) => activeFilter === 'all' || item.dataset.category === activeFilter);
            const shouldLimitPreview = Boolean(galleryMoreButton);

            galleryItems.forEach((item) => {
                const isMatch = filteredItems.includes(item);
                const itemIndex = filteredItems.indexOf(item);
                const shouldShow = isMatch && (!shouldLimitPreview || isExpanded || itemIndex < galleryPreviewLimit);
                item.hidden = !shouldShow;
                item.classList.toggle('is-hidden', !shouldShow);
            });

            if (galleryMoreButton) {
                const hasMore = filteredItems.length > galleryPreviewLimit;
                galleryMoreButton.hidden = !hasMore;
                galleryMoreButton.textContent = isExpanded ? 'Show Less Photos' : 'View More Photos';
            }
        };

        const openModal = (galleryItem) => {
            const image = galleryItem.querySelector('.gallery-image');
            const title = galleryItem.querySelector('h4')?.textContent ?? 'Gallery item';
            const subtitle = galleryItem.querySelector('p')?.textContent ?? '';

            lastFocusedElement = document.activeElement;
            modalImage.style.backgroundImage = image?.style.backgroundImage || 'none';
            modalTitle.textContent = title;
            modalMeta.textContent = subtitle;
            modalRoot.hidden = false;
            modalRoot.classList.add('is-open');
            modalRoot.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');

            window.setTimeout(() => modalCloseButton.focus(), 0);
        };

        const closeModal = () => {
            modalRoot.classList.remove('is-open');
            modalRoot.hidden = true;
            modalRoot.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');

            if (lastFocusedElement instanceof HTMLElement) {
                lastFocusedElement.focus();
            }
        };

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                filterButtons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');

                activeFilter = filter;
                isExpanded = false;
                updateGalleryView();

                if (modalRoot.classList.contains('is-open')) {
                    closeModal();
                }
            });
        });

        if (galleryMoreButton) {
            galleryMoreButton.addEventListener('click', (event) => {
                event.preventDefault();
                window.location.assign('gallery.php');
            });
        }

        galleryItems.forEach((galleryItem) => {
            const title = galleryItem.querySelector('h4')?.textContent ?? 'Gallery item';
            galleryItem.tabIndex = 0;
            galleryItem.setAttribute('role', 'button');
            galleryItem.setAttribute('aria-label', `Open ${title}`);

            const activate = () => {
                if (!galleryItem.hidden) {
                    openModal(galleryItem);
                }
            };

            galleryItem.addEventListener('click', activate);
            galleryItem.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    activate();
                }
            });
        });

        modalRoot.addEventListener('click', (event) => {
            if (event.target instanceof HTMLElement && event.target.hasAttribute('data-lightbox-close')) {
                closeModal();
            }
        });

        modalCloseButton.addEventListener('click', closeModal);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modalRoot.classList.contains('is-open')) {
                closeModal();
            }
        });

        updateGalleryView();
    }

    // --- Route event calls to action and contact form prefill behavior. ---
    function initEventActions() {
        const eventCta = document.querySelector('.events-cta');
        const eventLinks = [...document.querySelectorAll('.event-link')];
        const form = document.getElementById('contactForm');

        if (!form) {
            return;
        }

        const subjectField = form.querySelector('#subject');
        const messageField = form.querySelector('#message');

        const openContactForEvent = (title, date) => {
            const contactSection = document.getElementById('contact');
            const subjectValue = 'event';
            const detailMessage = title
                ? `Hello, I would like to know more about the event "${title}"${date ? ` on ${date}` : ''}. Please share the details and participation information.`
                : 'Hello, I would like to know more about the upcoming events and how to participate.';

            if (subjectField) {
                subjectField.value = subjectValue;
            }

            if (messageField) {
                messageField.value = detailMessage;
            }

            if (contactSection) {
                contactSection.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
            }

            if (subjectField) {
                subjectField.focus({ preventScroll: true });
            }
        };

        if (eventCta) {
            eventCta.addEventListener('click', () => {
                window.location.assign('events.php');
            });
        }

        eventLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const title = link.dataset.eventTitle || 'this event';
                const date = link.dataset.eventDate || '';
                openContactForEvent(title, date);
            });
        });
    }

    // --- Validate the contact form before submitting it to the PHP backend. ---
    function initContactForm() {
        const form = document.getElementById('contactForm');

        if (!form) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const status = document.createElement('p');
        status.className = 'form-status';
        status.setAttribute('aria-live', 'polite');
        status.hidden = true;

        if (submitButton) {
            form.insertBefore(status, submitButton);
        } else {
            form.appendChild(status);
        }

        const fields = {
            fullName: form.querySelector('#fullName'),
            email: form.querySelector('#email'),
            phone: form.querySelector('#phone'),
            subject: form.querySelector('#subject'),
            message: form.querySelector('#message')
        };

        Object.values(fields).forEach((field) => {
            if (!field) {
                return;
            }

            field.addEventListener('input', () => {
                clearFieldState(field);
                hideStatus();
            });

            field.addEventListener('change', () => {
                clearFieldState(field);
            });
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const fullName = fields.fullName?.value.trim() ?? '';
            const email = fields.email?.value.trim() ?? '';
            const subject = fields.subject?.value.trim() ?? '';
            const message = fields.message?.value.trim() ?? '';
            const phone = fields.phone?.value.trim() ?? '';

            const validations = [
                [fields.fullName, fullName.length > 1, 'Enter your full name.'],
                [fields.email, /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email), 'Enter a valid email address.'],
                [fields.subject, subject.length > 0, 'Choose a subject.'],
                [fields.message, message.length >= 10, 'Write a message with at least 10 characters.']
            ];

            if (phone && !/^[+()\d\s-]{7,}$/.test(phone)) {
                validations.push([fields.phone, false, 'Use a valid phone number format.']);
            }

            let isValid = true;

            validations.forEach(([field, valid, messageText]) => {
                if (!field) {
                    return;
                }

                markField(field, valid, messageText);
                isValid = isValid && valid;
            });

            if (!isValid) {
                showStatus('error', 'Please complete the highlighted fields before sending.');
                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
            }

            showStatus('success', 'Message validated. Sending it to the PHP backend...');

            window.setTimeout(() => {
                form.submit();
            }, 200);
        });

        function markField(field, isValid, messageText) {
            const group = field.closest('.form-group');

            if (!group) {
                return;
            }

            group.classList.toggle('is-invalid', !isValid);
            group.classList.toggle('is-valid', isValid);
            field.setAttribute('aria-invalid', String(!isValid));

            let message = group.querySelector('.field-message');

            if (!message) {
                message = document.createElement('small');
                message.className = 'field-message';
                group.appendChild(message);
            }

            message.textContent = isValid ? '' : messageText;
            message.hidden = isValid;
        }

        function clearFieldState(field) {
            const group = field.closest('.form-group');

            if (!group) {
                return;
            }

            group.classList.remove('is-invalid');
            group.classList.remove('is-valid');
            field.removeAttribute('aria-invalid');

            const message = group.querySelector('.field-message');
            if (message) {
                message.hidden = true;
                message.textContent = '';
            }
        }

        function showStatus(type, text) {
            status.className = `form-status ${type}`;
            status.textContent = text;
            status.hidden = false;
        }

        function hideStatus() {
            status.hidden = true;
            status.textContent = '';
            status.className = 'form-status';
        }
    }

    // --- Reveal content sections as they enter the viewport. ---
    function initReveals() {
        const revealTargets = document.querySelectorAll(
            '.section-header, .about-intro, .timeline-item, .value-card, .event-card, .camp-card, .gallery-item, .member-card, .contact-form-container, .contact-info-box, .social-links, .map-placeholder, .stat-box'
        );

        if (!revealTargets.length) {
            return;
        }

        revealTargets.forEach((target) => target.classList.add('reveal'));

        if (!('IntersectionObserver' in window)) {
            revealTargets.forEach((target) => target.classList.add('is-visible'));
            return;
        }

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        revealTargets.forEach((target) => revealObserver.observe(target));
    }

    // --- Animate the homepage statistics counters when they appear. ---
    function initCounters() {
        const counters = [...document.querySelectorAll('.stat-box h3')];

        if (!counters.length) {
            return;
        }

        const animateCounter = (counter) => {
            const targetText = counter.dataset.targetValue || counter.textContent.trim();
            const targetValue = Number.parseInt(targetText.replace(/\D/g, ''), 10) || 0;
            const suffix = targetText.replace(/[\d,]/g, '').trim();
            const duration = 1400;
            const startTime = performance.now();

            const step = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const currentValue = Math.floor(targetValue * eased);
                counter.textContent = `${currentValue.toLocaleString()}${suffix}`;

                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    counter.textContent = `${targetValue.toLocaleString()}${suffix}`;
                }
            };

            window.requestAnimationFrame(step);
        };

        if (!('IntersectionObserver' in window)) {
            counters.forEach((counter) => animateCounter(counter));
            return;
        }

        counters.forEach((counter) => {
            counter.dataset.targetValue = counter.textContent.trim();
            counter.textContent = '0';
        });

        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.6
        });

        counters.forEach((counter) => counterObserver.observe(counter));
    }
});
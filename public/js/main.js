document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Carousel JS Loaded");

  const wrapper  = document.querySelector('.category-carousel-wrapper');
  const carousel = document.querySelector('.category-carousel');
  const cards    = document.querySelectorAll('.category-card');
  const dots     = document.querySelectorAll('.dot');

  if (!wrapper || !carousel || cards.length === 0 || dots.length === 0) {
    console.error("❌ Missing wrapper/carousel/cards/dots");
    return;
  }

  const slidesToShow = 3;
  let currentSlide = 0;

  // Lấy khoảng cách (gap) thực tế từ CSS
  const styles = getComputedStyle(carousel);
  const gap = parseInt(styles.gap || styles.columnGap || "20", 10);

  function sizeViewport() {
    // chiều rộng thực tế của 1 card (sau khi ảnh load) 
    const cardWidth = cards[0].getBoundingClientRect().width;
    const viewport = cardWidth * slidesToShow + gap * (slidesToShow - 1);
    wrapper.style.maxWidth = viewport + "px";   // khóa bề ngang viewport đúng 3 card
  }

  function showSlide(index) {
    const totalSlides = Math.ceil(cards.length / slidesToShow);
    if (index < 0) index = 0;
    if (index >= totalSlides) index = totalSlides - 1;
    currentSlide = index;

    const cardWidth = cards[0].getBoundingClientRect().width;
    const offset = (cardWidth + gap) * slidesToShow * index;
    carousel.style.transform = `translateX(-${offset}px)`;

    dots.forEach((d, i) => d.classList.toggle('active', i === index));
  }

  // Gắn click dots
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const index = parseInt(dot.dataset.index, 10);
      showSlide(index);
    });
  });

  // Khởi tạo + responsive khi resize hoặc ảnh load xong
  sizeViewport();
  showSlide(0);
  window.addEventListener('resize', () => {
    sizeViewport();
    showSlide(currentSlide);
  });

  // Phòng khi ảnh load chậm làm thay đổi kích thước card
  const imgs = carousel.querySelectorAll('img');
  let left = imgs.length;
  imgs.forEach(img => {
    if (img.complete) { if (--left === 0) { sizeViewport(); showSlide(currentSlide); } }
    else img.addEventListener('load', () => { if (--left === 0) { sizeViewport(); showSlide(currentSlide); }});
  });

  // Auto-advance slides every 5 seconds
  const totalSlides = Math.ceil(cards.length / slidesToShow);
  setInterval(() => {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }, 5000);
});


document.addEventListener("DOMContentLoaded", () => {
    // Tab switcher
    const tabs = document.querySelectorAll(".tab-link");
    const panels = document.querySelectorAll(".tab-panel");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            panels.forEach(panel => panel.classList.remove("active"));
            document.querySelector(`#tab-${tab.dataset.id}`).classList.add("active");
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ Home JS loaded");

    // ===== Tabs =====
    const tabs = document.querySelectorAll(".tab-link");
    const panels = document.querySelectorAll(".tab-panel");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            panels.forEach(p => p.classList.remove("active"));
            const panel = document.querySelector(`#tab-${tab.dataset.id}`);
            if (panel) panel.classList.add("active");
        });
    });

    // ===== Sliders for each tab =====
    document.querySelectorAll(".tab-panel").forEach(panel => {
        const grid = panel.querySelector(".course-grid");
        const nextBtn = panel.querySelector(".arrow.next");
        const prevBtn = panel.querySelector(".arrow.prev");

        if (!grid || !nextBtn || !prevBtn) return;

        const cards = grid.querySelectorAll(".course-card");
        const visible = 4;
        const gap = 20;
        const cardWidth = cards[0].offsetWidth + gap;
        const totalSlides = Math.ceil(cards.length / visible) - 1;
        let currentSlide = 0;

        function updateSlider() {
            const offset = cardWidth * visible * currentSlide;
            grid.style.transform = `translateX(-${offset}px)`;

            // Ẩn nút ở đầu/cuối
            prevBtn.style.visibility = currentSlide === 0 ? "hidden" : "visible";
            nextBtn.style.visibility = currentSlide >= totalSlides ? "hidden" : "visible";
        }

        nextBtn.addEventListener("click", () => {
            if (currentSlide < totalSlides) {
                currentSlide++;
                updateSlider();
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlider();
            }
        });

        updateSlider();
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('userDropdown');

    if (avatarBtn && dropdown) {
        avatarBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
        });

        // Ẩn khi click ra ngoài
        document.addEventListener('click', () => {
            dropdown.style.display = 'none';
        });
    }

    // Sticky Header
    const header = document.getElementById('mainHeader');
    if (header) {
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 50) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
            lastScroll = currentScroll;
        });
    }

    // Animate numbers
    const animateNumbers = () => {
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count')) || 0;
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const updateNumber = () => {
                current += increment;
                if (current < target) {
                    stat.textContent = Math.floor(current);
                    requestAnimationFrame(updateNumber);
                } else {
                    stat.textContent = target;
                }
            };
            
            // Start animation when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateNumber();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(stat);
        });
    };
    
    animateNumbers();
});


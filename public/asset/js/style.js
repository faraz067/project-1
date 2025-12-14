/* License ID: DEVOMATE-SRC-20251214-8BIIS7SHXX | unknown@example.com | 2025-12-14 08:37:47 */
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
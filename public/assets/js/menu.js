const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const pageHeader = document.querySelector('.page-header');
const contentArea = document.querySelector('.content-area');


function toggleMenu() {
    hamburger.classList.toggle('active');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    
    if (window.innerWidth > 768) {
        if (pageHeader) {
            pageHeader.classList.toggle('shifted');
        }
        if (contentArea) {
            contentArea.classList.toggle('shifted');
        }
    }
}


if (hamburger) {
    hamburger.addEventListener('click', toggleMenu);
}

if (overlay) {
    overlay.addEventListener('click', toggleMenu);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
        toggleMenu();
    }
});

const currentPage = window.location.pathname.split('/').pop();
const menuLinks = document.querySelectorAll('.sidebar a');

menuLinks.forEach(link => {
    const linkHref = link.getAttribute('href');
    
    if (linkHref === currentPage || linkHref === './' + currentPage) {
        link.classList.add('active');
    }
    
    link.addEventListener('click', function() {
        menuLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});
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
const sidebar = document.querySelector('.sidebar');
const hamburger = document.querySelector('.sidebar-hamburger');
const navItems = document.querySelectorAll('.nav-list .nav-item');

if (hamburger) {
    hamburger.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('expanded');
    });
}

function setActive(element) {
    navItems.forEach(item => item.classList.remove('active'));
    element.classList.add('active');
}

navItems.forEach(item => {
    item.addEventListener('click', function() {
        setActive(this);
        
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('expanded');
        }
    });
});

//based on php
window.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    
    navItems.forEach(item => {
        const itemHref = item.getAttribute('href');
        if (itemHref === currentPath) {
            setActive(item);
        }
    });
});

document.addEventListener('click', (e) => {
    if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('expanded');
    }
});



/*profile sidebar*/

document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('adminProfileTrigger');
    const dropdown = document.getElementById('adminProfileDropdown');

    trigger.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevents immediate closing
        dropdown.classList.toggle('active');
    });

    // Close the popup if you click anywhere else on the screen
    window.addEventListener('click', function() {
        dropdown.classList.remove('active');
    });
});


/* NOTIFICATION */
const bellIcon = document.getElementById('bellIcon');
const notificationDropdown = document.getElementById('notificationDropdown');

bellIcon.addEventListener('click', function(e) {
    e.stopPropagation();
    notificationDropdown.classList.toggle('show');
    
    // Close admin profile dropdown if it's open (prevent overlapping)
    const adminDropdown = document.getElementById('adminProfileDropdown');
    if (adminDropdown) {
        adminDropdown.classList.remove('active'); // or 'show' depending on your class name
    }
});

// Close when clicking outside
window.addEventListener('click', function() {
    notificationDropdown.classList.remove('show');
});

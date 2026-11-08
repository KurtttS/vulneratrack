window.addEventListener('pageshow', function(event) {
    const isBackNavigation = event.persisted || 
    (window.performance && window.performance.getEntriesByType("navigation")[0].type === 'back_forward');
    if (isBackNavigation) {
        window.location.reload();
    }
});
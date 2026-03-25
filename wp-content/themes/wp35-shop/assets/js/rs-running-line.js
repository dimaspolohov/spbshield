document.addEventListener('DOMContentLoaded', function() {
    var headerTopLine = document.querySelector('.header_top_line');
    var line = document.getElementById('runningLine');

    if (!line || !headerTopLine) return;

    var items = Array.from(line.children);
    var originalItems = items.map(function(item) { return item.cloneNode(true); });

    function duplicateItems() {
        line.innerHTML = '';

        originalItems.forEach(function(item) {
            line.appendChild(item.cloneNode(true));
        });

        var screenWidth = window.innerWidth;
        var itemWidth = 288 + 48; // min-width + gap
        var itemsNeeded = Math.ceil(screenWidth / itemWidth) + originalItems.length + 3;

        for (var i = 0; i < itemsNeeded; i++) {
            originalItems.forEach(function(item) {
                line.appendChild(item.cloneNode(true));
            });
        }
    }

    duplicateItems();

    var isHovered = false;

    function pauseAnimation() {
        if (!isHovered) {
            isHovered = true;
            line.style.animationPlayState = 'paused';
        }
    }

    function resumeAnimation() {
        if (isHovered) {
            isHovered = false;
            line.style.animationPlayState = 'running';
        }
    }

    headerTopLine.addEventListener('mouseenter', pauseAnimation);
    headerTopLine.addEventListener('mouseleave', resumeAnimation);

    headerTopLine.addEventListener('mouseover', pauseAnimation);
    headerTopLine.addEventListener('mouseout', function(e) {
        if (!headerTopLine.contains(e.relatedTarget)) {
            resumeAnimation();
        }
    });

    var resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            duplicateItems();
        }, 250);
    });

    line.style.animationPlayState = 'running';
});

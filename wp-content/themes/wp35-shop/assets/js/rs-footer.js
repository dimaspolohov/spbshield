/* Size selector radio button sync */
jQuery(document).ready(function() {
    jQuery('body').on('change', '[name="size"]', function() {
        var id = jQuery(this).attr('id');
        jQuery('[name="size"]').each(function() {
            jQuery(this).removeAttr('checked').removeClass('checked');
        });
        jQuery('[name="size"][id="' + id + '"]').attr('checked', 'checked').addClass('checked');
    });
});

/* Cookie consent popup */
document.addEventListener('DOMContentLoaded', function() {
    var popup = document.getElementById('cookie-popup');
    var btn = document.getElementById('cookie-ok');
    var cookieKey = 'cookie_consent';

    if (popup && btn) {
        if (!localStorage.getItem(cookieKey)) {
            popup.style.display = 'flex';
        }

        btn.addEventListener('click', function() {
            localStorage.setItem(cookieKey, 'true');
            popup.style.display = 'none';
        });
    }
});

/* AOS (Animate On Scroll) initialization */
jQuery(document).ready(function() {
    AOS.init({
        duration: 500,
        easing: 'ease-out-quart',
        once: true
    });
});

/* Fix broken images — double .webp.webp extensions and hide failed loads */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('source[srcset*=".webp.webp"]').forEach(function(source) {
        var oldSrcset = source.getAttribute('srcset');
        var newSrcset = oldSrcset.replace(/\.webp\.webp/g, '.webp');
        source.setAttribute('srcset', newSrcset);
    });

    document.querySelectorAll('img[src*=".webp.webp"]').forEach(function(img) {
        var oldSrc = img.getAttribute('src');
        var newSrc = oldSrc.replace(/\.webp\.webp/g, '.webp');
        img.setAttribute('src', newSrc);
    });

    function hideBrokenImages() {
        document.querySelectorAll('img').forEach(function(img) {
            img.addEventListener('error', function() {
                var slideParent = this.closest('.rs-thumbs__slide-slide');
                if (slideParent) {
                    slideParent.style.display = 'none';
                } else {
                    this.style.display = 'none';
                }
            });
        });
    }

    setTimeout(function() {
        document.querySelectorAll('.rs-thumbs__slide-slide img, .rs-product__slider img').forEach(function(img) {
            if (img.src.includes('.webp.webp') || img.naturalWidth === 0) {
                var slideParent = img.closest('.rs-thumbs__slide-slide') || img.closest('.rs-product__slide-slide');
                if (slideParent) {
                    slideParent.style.display = 'none';
                }
            }
        });
    }, 500);

    hideBrokenImages();
});

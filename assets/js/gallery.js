// Gallery JavaScript for Makerere Competent High School

document.addEventListener('DOMContentLoaded', function() {
    initGallery();
    initLightbox();
    initGalleryFilters();
});

// Gallery Initialization
function initGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');

    galleryItems.forEach((item, index) => {
        // Add click event for lightbox
        item.addEventListener('click', function() {
            openLightbox(index);
        });

        // Add hover effects
        item.addEventListener('mouseenter', function() {
            const overlay = this.querySelector('.gallery-overlay');
            if (overlay) {
                overlay.style.opacity = '1';
            }
        });

        item.addEventListener('mouseleave', function() {
            const overlay = this.querySelector('.gallery-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
            }
        });

        // Add loading state
        const img = item.querySelector('img');
        if (img) {
            img.addEventListener('load', function() {
                item.classList.add('loaded');
            });
        }
    });
}

// Lightbox Functionality
let currentImageIndex = 0;
let galleryImages = [];

function initLightbox() {
    // Collect all gallery images
    const galleryItems = document.querySelectorAll('.gallery-item img');
    galleryImages = Array.from(galleryItems).map(img => ({
        src: img.src,
        alt: img.alt || '',
        title: img.closest('.gallery-item').querySelector('.gallery-overlay h4') ? img.closest('.gallery-item').querySelector('.gallery-overlay h4').textContent : ''
    }));

    // Create lightbox HTML
    createLightboxHTML();
}

function createLightboxHTML() {
    const lightboxHTML = `
        <div id="gallery-lightbox" class="lightbox">
            <div class="lightbox-overlay"></div>
            <div class="lightbox-container">
                <button class="lightbox-close">&times;</button>
                <button class="lightbox-prev">&#8249;</button>
                <button class="lightbox-next">&#8250;</button>
                <div class="lightbox-content">
                    <img src="" alt="" class="lightbox-image">
                    <div class="lightbox-caption">
                        <h4></h4>
                        <p class="image-counter"></p>
                    </div>
                </div>
                <div class="lightbox-thumbnails">
                    <div class="thumbnails-container"></div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', lightboxHTML);

    // Add event listeners
    const lightbox = document.getElementById('gallery-lightbox');
    const closeBtn = lightbox.querySelector('.lightbox-close');
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    const overlay = lightbox.querySelector('.lightbox-overlay');

    closeBtn.addEventListener('click', closeLightbox);
    prevBtn.addEventListener('click', () => navigateLightbox('prev'));
    nextBtn.addEventListener('click', () => navigateLightbox('next'));
    overlay.addEventListener('click', closeLightbox);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox.classList.contains('active')) {
            switch (e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    navigateLightbox('prev');
                    break;
                case 'ArrowRight':
                    navigateLightbox('next');
                    break;
            }
        }
    });

    // Add CSS styles
    addLightboxStyles();
}

function addLightboxStyles() {
    const styles = `
        <style id="lightbox-styles">
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .lightbox.active {
            display: flex;
            opacity: 1;
        }
        
        .lightbox-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
        }
        
        .lightbox-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            text-align: center;
        }
        
        .lightbox-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        
        .lightbox-caption {
            color: white;
            margin-top: 15px;
            padding: 0 20px;
        }
        
        .lightbox-caption h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .image-counter {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .lightbox-close,
        .lightbox-prev,
        .lightbox-next {
            position: absolute;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .lightbox-close {
            top: 20px;
            right: 20px;
            font-size: 2.5rem;
            line-height: 1;
        }
        
        .lightbox-prev {
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .lightbox-next {
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .lightbox-close:hover,
        .lightbox-prev:hover,
        .lightbox-next:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .lightbox-next:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .lightbox-prev:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .lightbox-thumbnails {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            max-width: 80%;
            overflow-x: auto;
        }
        
        .thumbnails-container {
            display: flex;
            gap: 10px;
            padding: 10px;
        }
        
        .thumbnail {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
            border: 2px solid transparent;
        }
        
        .thumbnail.active {
            opacity: 1;
            border-color: #ffd700;
        }
        
        .thumbnail:hover {
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .lightbox-container {
                padding: 10px;
            }
            
            .lightbox-content {
                max-width: 95%;
                max-height: 95%;
            }
            
            .lightbox-image {
                max-height: 70vh;
            }
            
            .lightbox-close,
            .lightbox-prev,
            .lightbox-next {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }
            
            .lightbox-close {
                top: 10px;
                right: 10px;
            }
            
            .lightbox-prev {
                left: 10px;
            }
            
            .lightbox-next {
                right: 10px;
            }
            
            .lightbox-thumbnails {
                display: none;
            }
        }
        </style>
    `;

    document.head.insertAdjacentHTML('beforeend', styles);
}

function openLightbox(index) {
    currentImageIndex = index;
    const lightbox = document.getElementById('gallery-lightbox');

    updateLightboxContent();
    createThumbnails();

    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('gallery-lightbox');
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
}

function navigateLightbox(direction) {
    if (direction === 'next') {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    } else {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    }

    updateLightboxContent();
    updateActiveThumbnail();
}

function updateLightboxContent() {
    const lightbox = document.getElementById('gallery-lightbox');
    const image = lightbox.querySelector('.lightbox-image');
    const title = lightbox.querySelector('.lightbox-caption h4');
    const counter = lightbox.querySelector('.image-counter');

    const currentImage = galleryImages[currentImageIndex];

    image.src = currentImage.src;
    image.alt = currentImage.alt;
    title.textContent = currentImage.title;
    counter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
}

function createThumbnails() {
    const container = document.querySelector('.thumbnails-container');
    container.innerHTML = '';

    galleryImages.forEach((image, index) => {
        const thumb = document.createElement('img');
        thumb.src = image.src;
        thumb.alt = image.alt;
        thumb.className = `thumbnail ${index === currentImageIndex ? 'active' : ''}`;

        thumb.addEventListener('click', () => {
            currentImageIndex = index;
            updateLightboxContent();
            updateActiveThumbnail();
        });

        container.appendChild(thumb);
    });
}

function updateActiveThumbnail() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach((thumb, index) => {
        thumb.classList.toggle('active', index === currentImageIndex);
    });
}

// Gallery Filters
function initGalleryFilters() {
    const filterButtons = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filter gallery items
            filterGalleryItems(filter, galleryItems);
        });
    });
}

function filterGalleryItems(filter, items) {
    items.forEach(item => {
        const category = item.getAttribute('data-category');

        if (filter === 'all' || category === filter) {
            item.style.display = 'block';
            item.style.animation = 'fadeIn 0.5s ease';
        } else {
            item.style.display = 'none';
        }
    });

    // Update gallery images array for lightbox
    updateGalleryImagesArray();
}

function updateGalleryImagesArray() {
    const visibleItems = document.querySelectorAll('.gallery-item[style*="block"], .gallery-item:not([style*="none"])');
    galleryImages = Array.from(visibleItems).map(item => {
        const img = item.querySelector('img');
        return {
            src: img.src,
            alt: img.alt || '',
            title: item.querySelector('.gallery-overlay h4') ? item.querySelector('.gallery-overlay h4').textContent : ''
        };
    });
}

// Gallery Upload (for admin)
function initGalleryUpload() {
    const uploadForm = document.getElementById('gallery-upload-form');

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            uploadGalleryImages(formData);
        });
    }

    // Drag and drop functionality
    const dropZone = document.querySelector('.gallery-drop-zone');

    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            handleFileUpload(files);
        });
    }
}

function uploadGalleryImages(formData) {
    // This would typically make an AJAX call to upload.php
    console.log('Uploading gallery images...');

    // Simulate upload progress
    showUploadProgress();
}

function handleFileUpload(files) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    Array.from(files).forEach(file => {
        if (!validTypes.includes(file.type)) {
            alert(`${file.name} is not a valid image type.`);
            return;
        }

        if (file.size > maxSize) {
            alert(`${file.name} is too large. Maximum size is 5MB.`);
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            createImagePreview(e.target.result, file.name);
        };
        reader.readAsDataURL(file);
    });
}

function createImagePreview(src, name) {
    const previewContainer = document.querySelector('.upload-previews');

    if (previewContainer) {
        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';
        previewDiv.innerHTML = `
            <img src="${src}" alt="${name}">
            <div class="preview-info">
                <p>${name}</p>
                <button type="button" class="remove-preview" onclick="this.parentElement.parentElement.remove()">Remove</button>
            </div>
        `;

        previewContainer.appendChild(previewDiv);
    }
}

function showUploadProgress() {
    // Create progress modal or notification
    const progressHTML = `
        <div class="upload-progress-modal">
            <div class="progress-content">
                <h3>Uploading Images...</h3>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <p class="progress-text">0%</p>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', progressHTML);

    // Simulate progress
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        const progressFill = document.querySelector('.progress-fill');
        const progressText = document.querySelector('.progress-text');

        if (progressFill && progressText) {
            progressFill.style.width = progress + '%';
            progressText.textContent = progress + '%';
        }

        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                document.querySelector('.upload-progress-modal') ? .remove();
                showNotification('Images uploaded successfully!', 'success');
            }, 500);
        }
    }, 200);
}

// Gallery Slideshow
function initSlideshow() {
    const slideshowContainer = document.querySelector('.gallery-slideshow');

    if (slideshowContainer) {
        const images = slideshowContainer.querySelectorAll('img');
        let currentSlide = 0;

        // Auto-advance slideshow
        setInterval(() => {
            images[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % images.length;
            images[currentSlide].classList.add('active');
        }, 5000);
    }
}

// Export functions for use in other scripts
window.galleryFunctions = {
    openLightbox,
    closeLightbox,
    navigateLightbox,
    filterGalleryItems,
    initGalleryUpload
};
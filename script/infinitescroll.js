document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let loading = false;
    const container = document.querySelector('.blog-container');
    const loadingElement = document.querySelector('.loading');

    async function loadImages() {
        if (loading) return;
        loading = true;
        
        loadingElement.style.display = 'block';
        
        try {
            const response = await fetch(`block/infinitescroll?page=${currentPage}`);
            const data = await response.json();
            
            if (data.error) {
                loadingElement.textContent = 'Erreur de chargement';
                return;
            }

            data.forEach(image => {
                const div = document.createElement('div');
                div.className = 'blog-item';
                div.innerHTML = `
                    <div class="blog-info"><span>${image.title}</span>Publiée le : ${image.date}</div>
                    <img src="${image.path}" alt="${image.title}" class="blog-image">
                `;
                container.appendChild(div);
            });

            currentPage++;
            
        } catch (error) {
            loadingElement.textContent = 'Erreur de chargement';
        } finally {
            loading = false;
            loadingElement.style.display = 'none';
        }
    }

    function handleScroll() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            loadImages();
        }
    }

    // Initial load
    loadImages();

    // Event listeners
    window.addEventListener('scroll', handleScroll);
});
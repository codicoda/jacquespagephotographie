document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form.contact');
    const messageResult = document.getElementById('messageResult');
  

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
       
        fetch('script/send', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            messageResult.innerHTML = data;
            if (data.includes('succès')) {
                form.reset();
            }
        })
        .catch(error => {
            messageResult.innerHTML = "❌ Une erreur est survenue, veuillez réessayer.";
        });
    });
});

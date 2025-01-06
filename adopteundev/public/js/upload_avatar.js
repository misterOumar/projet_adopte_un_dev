    document.querySelector('.upload-btn').addEventListener('click', () => {
        document.getElementById('avatar-input').click();
    });

    document.getElementById('avatar-input').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('save-avatar-btn').addEventListener('click', () => {
        const input = document.getElementById('avatar-input');
        if (input.files.length > 0) {
            const formData = new FormData();
            formData.append('avatar', input.files[0]);

            fetch('/upload-avatar', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    alert('Avatar mis à jour avec succès !');
                })
                .catch((error) => {
                    console.error('Erreur lors de l’envoi de l’avatar :', error);
                    alert('Erreur lors de la mise à jour de l’avatar.');
                });
        } else {
            alert('Veuillez importer une image avant de sauvegarder.');
        }
    });
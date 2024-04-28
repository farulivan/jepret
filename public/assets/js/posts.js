const baseUrl = '';

function getPosts() {
    const url = baseUrl + '/posts';

    fetch(url)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}


function uploadPhoto() {
    const url = baseUrl + '/posts';
    const file = document.getElementById('photo').files[0];
    const formData = new FormData();
    formData.append('photo', file);

    const options = {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        body: formData
    };

    fetch(url, options)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}

function createPhoto() {
    const url = baseUrl + '/posts';
    const caption = document.getElementById('caption').value;
    const photoUrl = document.getElementById('photoUrl').value;
    const body = JSON.stringify({caption, photoUrl});

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        body
    };

    fetch(url, options)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}
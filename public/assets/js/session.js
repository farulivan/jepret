const baseUrl = '';
let intervalId
// in second
let intervalSecond = 10

function login(email, password) {
    url = baseUrl + '/session';

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({email, password})
    };

    fetch(url, options)
        .then(response => response.json())
        .then(data => {
            console.log({data});
            if (data.ok) {
                localStorage.setItem('access_token', data.data.access_token);
                localStorage.setItem('refresh_token', data.data.refresh_token);
            }
            // redirect to /main
            window.location.href = '/main';
        })
        .catch(error => console.error('Error:', error));
}

function refreshToken() {
    const url = baseUrl + '/session';
    const options = {
        method: 'POST',
        data: JSON.stringify({_method: 'PUT'}),
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('refresh_token')
        }
    };

    fetch(url, options)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.ok) {
                localStorage.setItem('access_token', data.data.access_token);
            }
        })
        .catch(error => console.error('Error:', error));
}

function startRefreshingToken() {
    // Refresh the token right away
    refreshToken();

    // Then refresh the token every 20 seconds
    intervalId = setInterval(refreshToken, intervalSecond * 1_000);
}

function stopRefreshingToken() {
    clearInterval(intervalId);
}
const baseUrl = ""; // Ensure you use "http://" for local testing
let intervalId;
// in second
let intervalSecond = 20; // Updated to match the access token's lifetime

function login(email, password) {
    const url = baseUrl + "/session";

    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
    };

    fetch(url, options)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Login failed");
            }
            return response.json();
        })
        .then((data) => {
            console.log({ data });
            if (data.ok) {
                localStorage.setItem("access_token", data.data.access_token);
                localStorage.setItem("refresh_token", data.data.refresh_token);
                // Set token refresh to start
                startRefreshingToken();
                // redirect to /main
                window.location.href = "/main";
            } else {
                throw new Error("Login failed with data status not ok");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Login failed: " + error.message); // Optionally add user feedback on failure
        });
}

function refreshToken() {
    const url = baseUrl + "/session";
    const refreshToken = localStorage.getItem("refresh_token");

    if (!refreshToken) {
        console.error("No refresh token available");
        alert(
            "Session error: No refresh token available. Please log in again."
        );
        window.location.href = "/login"; // Redirect to login page or appropriate route
        return;
    }

    const options = {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + refreshToken,
        },
    };

    fetch(url, options)
        .then((response) => {
            if (!response.ok) {
                if (response.status === 401) {
                    // Handle invalid refresh token
                    throw new Error(
                        "Invalid refresh token, please log in again"
                    );
                }
                throw new Error("Failed to refresh token");
            }
            return response.json();
        })
        .then((data) => {
            console.log(data);
            if (data.ok) {
                localStorage.setItem("access_token", data.data.access_token);
                localStorage.setItem("accessTokenExpiry", Date.now() + 20000); // 20 seconds from now
                console.log("Access token refreshed successfully");
            } else {
                throw new Error("Token refresh failed with data status not ok");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert(error.message); // Optionally add user feedback on failure
            window.location.href = "/login"; // Redirect to login page or appropriate route
        });
}

function startRefreshingToken() {
    // Refresh the token right away
    refreshToken();

    // Then refresh the token every 20 seconds
    intervalId = setInterval(refreshToken, intervalSecond * 1000);
}

function stopRefreshingToken() {
    clearInterval(intervalId);
}

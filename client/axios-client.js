import axios from "axios";

const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_API_BASE_URL}`, // Ensure you have the right base URL
});

// Add a request interceptor to include the auth token for every request
axiosClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("ACCESS_TOKEN");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add a response interceptor to handle token refresh logic
axiosClient.interceptors.response.use(
    (response) => {
        return response;
    },
    async (error) => {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true; // Mark this request as a retry attempt
            const refreshToken = localStorage.getItem("REFRESH_TOKEN");
            if (refreshToken) {
                try {
                    const tokenResponse = await axios.put(
                        `${import.meta.env.VITE_API_BASE_URL}/session`,
                        {},
                        {
                            headers: {
                                Authorization: `Bearer ${refreshToken}`,
                            },
                        }
                    );
                    const { access_token } = tokenResponse.data.data;
                    localStorage.setItem("ACCESS_TOKEN", access_token);
                    axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${access_token}`;
                    originalRequest.headers[
                        "Authorization"
                    ] = `Bearer ${access_token}`;
                    return axiosClient(originalRequest); // Retry the original request with the new access token
                } catch (refreshError) {
                    console.log("Unable to refresh token", refreshError);
                    // Optionally redirect to login or logout the user
                    return Promise.reject(refreshError);
                }
            } else {
                // No refresh token available, redirect or logout
                console.log("No refresh token available");
            }
        }
        return Promise.reject(error);
    }
);

export default axiosClient;

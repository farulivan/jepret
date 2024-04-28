import { createContext, useContext, useState } from "react";

const StateContext = createContext({
    currentUser: null,
    accessToken: null,
    refreshToken: null,
    setUser: () => {},
    setAccessToken: () => {},
    setRefreshToken: () => {},
});

export const ContextProvider = ({ children }) => {
    const [user, _setUser] = useState(JSON.parse(localStorage.getItem("USER")));
    const [accessToken, _setAccessToken] = useState(
        localStorage.getItem("ACCESS_TOKEN")
    );
    const [refreshToken, _setRefreshToken] = useState(
        localStorage.getItem("REFRESH_TOKEN")
    );

    const setUser = (userData) => {
        _setUser(userData);
        if (userData) {
            localStorage.setItem("USER", JSON.stringify(userData));
        } else {
            localStorage.removeItem("USER");
        }
    };

    const setAccessToken = (token) => {
        _setAccessToken(token);
        if (token) {
            localStorage.setItem("ACCESS_TOKEN", token);
        } else {
            localStorage.removeItem("ACCESS_TOKEN");
        }
    };

    const setRefreshToken = (token) => {
        _setRefreshToken(token);
        if (token) {
            localStorage.setItem("REFRESH_TOKEN", token);
        } else {
            localStorage.removeItem("REFRESH_TOKEN");
        }
    };

    const logout = () => {
        localStorage.removeItem("ACCESS_TOKEN");
        localStorage.removeItem("REFRESH_TOKEN");
        setUser(null);
        setAccessToken(null);
        setRefreshToken(null);
    };

    return (
        <StateContext.Provider
            value={{
                user,
                setUser,
                accessToken,
                setAccessToken,
                refreshToken,
                setRefreshToken,
                logout,
            }}
        >
            {children}
        </StateContext.Provider>
    );
};

export const useStateContext = () => useContext(StateContext);

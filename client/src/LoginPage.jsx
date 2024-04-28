import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axiosClient from "../axios-client";
import { useStateContext } from "./context/ContextProvider";

function LoginPage() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    // const [error, setError] = useState("");
    const navigate = useNavigate();
    const { setAccessToken, setRefreshToken, setUser } = useStateContext();

    const handleLogin = async (e) => {
        e.preventDefault();

        const payload = {
            email,
            password,
        };

        axiosClient
            .post("/session", payload)
            .then(({ data }) => {
                if (data.ok) {
                    setUser(data.data.user);
                    setAccessToken(data.data.access_token);
                    setRefreshToken(data.data.refresh_token);
                    navigate("/"); // Redirect on success
                } else {
                    alert(`Error: ${data.msg}`);
                }
            })
            .catch((err) => {
                if (err.response) {
                    const { status, data } = err.response;
                    if (status === 400 || status === 401) {
                        alert(`Error: ${data.msg}`);
                    } else {
                        console.log(err);
                        alert("An error occurred. Please try again later.");
                    }
                } else {
                    alert("Network error. Please check your connection.");
                }
            });
    };

    return (
        <main className="row is-fullheight">
            <div className="col-md-12">
                <div id="loginFormLayout">
                    <article id="loginFormHolder">
                        <hgroup>
                            <h1>Jepret Kuy</h1>
                            <h2>Mudah berbagi foto-fotomu!</h2>
                        </hgroup>
                        <form onSubmit={handleLogin}>
                            <input
                                type="email"
                                placeholder="Email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                            />
                            <input
                                type="password"
                                placeholder="Password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                            />
                            <button type="submit" className="contrast">
                                Login
                            </button>
                        </form>
                    </article>
                </div>
            </div>
        </main>
    );
}

export default LoginPage;

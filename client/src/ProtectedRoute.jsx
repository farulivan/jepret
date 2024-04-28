import { Navigate } from "react-router-dom";
import { useStateContext } from "./context/ContextProvider"; // Adjust import path as needed

function ProtectedRoute({ children }) {
    const { accessToken } = useStateContext();

    if (!accessToken) {
        // Redirect to login page if no access token found
        return <Navigate to="/login" />;
    }

    return children;
}

export default ProtectedRoute;

import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import LoginPage from "./LoginPage";
import MainPage from "./MainPage";
import ProtectedRoute from "./ProtectedRoute";
function App() {
    return (
        <Router>
            <Routes>
            <Route path="/login" element={<LoginPage />} />
                <Route path="/main" element={<ProtectedRoute><MainPage /></ProtectedRoute>} />
                <Route path="/" element={<ProtectedRoute><MainPage /></ProtectedRoute>} />
            </Routes>
        </Router>
    );
}

export default App;
